<?php

class TournamentController
{
    public function ListScheduledAction()
    {
        Middleware::postMethod();
        Middleware::isAdmin();

        try {
            $stmt = DB::Connection()->prepare("SELECT ID, StartTime FROM Tournament WHERE HasEnded='false' ORDER BY StartTime");
            if ($stmt->execute()) {
                $results = $stmt->fetchAll();

                echo blade()->run("ViewParts.ScheduledGamesList", [
                    "scheduledGames" => $results,
                ]);
            } else {
                Redirect::internalServerError();
            }
        } catch (Exception $exception) {
            Redirect::internalServerError();
        }
    }

    public function AddNewAction()
    {
        Middleware::postMethod();
        Middleware::isAdmin();

        $time = isset($_POST["time"]) ? trim(filter_input(INPUT_POST, "time", FILTER_SANITIZE_STRING)) : "";
        if ($time == "") {
            echo 0;
            die();
        }

        $date = isset($_POST["date"]) ? trim(filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING)) : "";
        if ($date == "") {
            echo 0;
            die();
        }
        try {
            $stmt = DB::Connection()->prepare("INSERT INTO Tournament(StartTime) VALUES (:startTime)");
            $stmt->bindValue("startTime", $date . " " . $time . ":00");
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                echo 1;
            } else {
                echo 0;
            }
        } catch (Exception $exception) {
            Redirect::internalServerError();
        }

    }

    public function RemoveGameAction()
    {
        Middleware::postMethod();
        Middleware::isAdmin();

        $id= isset($_POST["id"]) ? trim(filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING)) : "";
        if($id = ""){
            Redirect::badRequest();
        }

        try {
            $stmt = DB::Connection()->prepare("DELETE FROM Tournament WHERE ID = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            if($stmt->rowCount() == 1){
                echo 1;
            }elseif ($stmt->rowCount() > 1){
                echo 2;
            }else {
                echo 0;
            }

        }catch (Exception $exception){
            Redirect::internalServerError();
        }
    }

    public function SelectGameAction(){

        Middleware::postMethod();
        Middleware::isAdmin();

        $id= isset($_POST["id"]) ? trim(filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING)) : "";

        try{
            $settingStmt = DB::Connection()->prepare("SELECT Settings FROM Tournament WHERE ID = :id");
            $settingStmt->bindValue(":id", $id);
            $settingStmt->execute();


            $getPlayersStmt = DB::Connection()->prepare("SELECT ID, UserName FROM User WHERE ID = (SELECT UserID FROM GameStatistics WHERE TournamentID = :id)");
            $getPlayersStmt->bindValue(":id", $id);
            $getPlayersStmt->execute();

            $playerList = $getPlayersStmt->fetch();
            echo blade()->run("GamePlayerList", [
                "playerList" => $playerList
                ]);


            $settings = $settingStmt->fetchColumn();
            echo blade()->run("GameSettings", [
                "playerList" => $playerList
            ]);


        }catch (Exception $exception){
            Redirect::internalServerError();
        }
    }

    public function ListTablesAction() {
        try {
            $stmt = DB::Connection()->prepare("SELECT ID FROM Tournament WHERE HasStarted='true'");
            $stmt->execute();
            $tournamentID = $stmt->fetchColumn();

            if ($tournamentID) {
                $stmt = DB::Connection()->prepare("SELECT DISTINCT CurrentTable FROM GameStatistics WHERE TournamentID=:tournamentID");
                $stmt->bindValue("tournamentID", $tournamentID);
                $stmt->execute();
                $gameStatistics = $stmt->fetchAll();

                $filledTables = array();

                foreach ($gameStatistics as $gameStatistic) {
                    $stmt = DB::Connection()->prepare("SELECT Username FROM User WHERE ID=(SELECT UserID FROM GameStatistics WHERE TournamentID =:tournamentID AND CurrentTable=:currentTable)");
                    $stmt->bindValue("tournamentID", $tournamentID);
                    $stmt->bindValue("currentTable", $gameStatistic["CurrentTable"]);
                    $stmt->execute();
                    push_array($filledTable, $stmt->fetchAll());
                }

                echo blade()->run("ViewParts.listTables", [
                    "tables" => $filledTables,
                ]);
            }
            else {
                Redirect::locked();
            }
        }
        catch (Exception $e) {
            Redirect::internalServerError();
        }
    }
}

?>