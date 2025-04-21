<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
include 'db.inc.php';

function createUser(): bool
{
    global $pdo;
    $userData = $_SESSION['user_signUp'];

    $userID = "";
    $check = "";
    do {
        $userID = "";
        for ($i = 0; $i < 10; $i++) {
            $userID .= random_int(0, 9);
        }
        $query = "SELECT user_id FROM Users WHERE user_id = :userID";
        $operation = $pdo->prepare($query);
        $operation->execute([':userID' => $userID]);
        $check = $operation->fetch();
    } while ($check != null);

    $sql = 'INSERT INTO Users (user_id,name, address, date_of_birth, id_number, email, role, telephone, qualification, skills, username, password) 
        VALUES (:user_id,:name, :address, :date_of_birth, :id_number, :email, :role, :telephone, :qualification, :skills, :username, :password)';
    $operation = $pdo->prepare($sql);
    try {
        $operation->execute(
            array(
                ':user_id' => $userID,
                ':name' => $userData['Name'],
                ':address' => $userData['House'] . " " . $userData['Street'] . " " . $userData['City'] . " " . $userData['Country'],
                ':date_of_birth' => $userData['DateOfBirth'],
                ':id_number' => $userData['IDNumber'],
                ':email' => $userData['Email'],
                ':role' => $userData['Role'],
                ':telephone' => $userData['Telephone'],
                ':qualification' => $userData['Qualification'],
                ':skills' => $userData['Skills'],
                ':username' => $userData['UserName'],
                ':password' => $userData['Password']
            )
        );

        if ($operation->rowCount() > 0) {
            $sql = 'SELECT * FROM Users WHERE user_id = :user_id';
            $operation = $pdo->prepare($sql);
            $operation->execute([':user_id' => $userID]);
            $user = $operation->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $_SESSION['user'] = $user;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo $e->getMessage();
        return false;
    }
}

function userExists($UserName): bool
{
    global $pdo;

    $sql = 'SELECT * From Users Where username = :username';
    $operation = $pdo->prepare($sql);
    $operation->execute(array(':username' => $UserName));

    $user = $operation->fetch();
    if ($user != null) {
        return true;
    }
    return false;
}
function emailExists($email): bool
{
    global $pdo;

    $sql = 'SELECT * From Users Where email = :email';
    $operation = $pdo->prepare($sql);
    $operation->execute(array(':email' => $email));

    $user = $operation->fetch();
    if ($user != null) {
        return true;
    }
    return false;
}

function userLogin($username, $password): bool
{
    global $pdo;

    $sql = 'SELECT * FROM Users WHERE username = :username AND password = :password';
    $operation = $pdo->prepare($sql);
    $operation->execute([':username' => $username, ':password' => $password]);

    $user = $operation->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

function createProject(): bool
{
    global $pdo;

    $projectData = $_SESSION['project_data'];

    if (projectExists($projectData['ProjectID']))
        return false;

    $sql = 'INSERT INTO Projects(project_id, title, description, customer_name, budget, start_date, end_date)
                VALUES (:project_id, :title, :description, :customer_name, :budget, :start_date, :end_date)';
    $operation = $pdo->prepare($sql);

    $operation->execute(array(
        ':project_id' => $projectData['ProjectID'],
        ':title' => $projectData['ProjectTitle'],
        ':description' => $projectData['ProjectDescription'],
        ':customer_name' => $projectData['CustomerName'],
        ':budget' => $projectData['Budget'],
        ':start_date' => $projectData['StartDate'],
        ':end_date' => $projectData['EndDate']
    ));
    return $operation->rowCount() > 0;
}
function getProjects()
{
    global $pdo;
    $sql = 'SELECT * FROM Projects';
    $operation = $pdo->prepare($sql);
    $operation->execute();
    return $operation->fetchAll(PDO::FETCH_ASSOC);
}
function getProjectsManagedBy($user_id)
{
    global $pdo;
    $sql = 'SELECT * FROM Projects WHERE manager_id = :user_id';
    $operation = $pdo->prepare($sql);
    $operation->execute([':user_id' => $user_id]);
    return $operation->fetchAll(PDO::FETCH_ASSOC);
}
function getProject($project_id)
{
    global $pdo;
    $sql = 'SELECT * FROM Projects WHERE project_id = :project_id';
    $operation = $pdo->prepare($sql);
    $operation->execute([':project_id' => $project_id]);
    return $operation->fetch(PDO::FETCH_ASSOC);
}

function getNoLeaderProjects()
{
    global $pdo;
    $sql = 'SELECT * FROM Projects WHERE manager_id IS NULL ORDER BY start_date';
    $operation = $pdo->prepare($sql);
    $operation->execute();
    return $operation->fetchAll(PDO::FETCH_ASSOC);
}
function projectExists($project_id): bool
{
    global $pdo;

    $sql = 'SELECT * From Projects Where project_id = :project_id';
    $operation = $pdo->prepare($sql);
    $operation->execute(array(':project_id' => $project_id));

    $project = $operation->fetch();
    if ($project != null) {
        return true;
    }
    return false;
}
function allocateProjectLeader($user_id, $project_id)
{
    global $pdo;
    $setManager = "UPDATE Projects SET manager_id = :user_id WHERE project_id = :project_id";
    $result = $pdo->prepare($setManager);
    try {

        $result->execute([':user_id' => $user_id, ':project_id' => $project_id]);
        return $result->rowCount() > 0;
    } catch (PDOException $e) {
        return ("couldn't allocate project leader: " . $e->getMessage());
    }
    //TODO  :check it worked
}
function insertFile($file): bool
{
    global $pdo;

    //all files are stored in the uploads folder
    $filePath = "../uploads";
    $sql = "INSERT INTO Files(name,path,project_id)
        VALUES (:name,:path,:project_id)";

    $operation = $pdo->prepare($sql);
    $operation->execute(
        array(
            ':name' => $file['name'],
            ':path' => $filePath . "/" . $file['name'],
            ':project_id' => $file['project_id']
        )
    );
    return $operation->rowCount() > 0;
}
function getLeaders()
{
    global $pdo;
    $sql = 'SELECT * FROM Users WHERE role = "Project Leader"';
    $operation = $pdo->prepare($sql);
    $operation->execute();
    return $operation->fetchAll(PDO::FETCH_ASSOC);

}
function getMembers()
{
    global $pdo;
    $sql = 'SELECT * FROM Users WHERE role = "Team Member"';
    $operation = $pdo->prepare($sql);
    $operation->execute();
    return $operation->fetchAll(PDO::FETCH_ASSOC);

}
function createTask(): bool
{
    global $pdo;
    $taskData = $_SESSION['task_data'];
    $sql = 'INSERT INTO Tasks(name,project_id,description,start_date,end_date,effort,status,priority)
        VALUES(:name,:project_id,:description,:start_date,:end_date,:effort,:status,:priority)';
    $operation = $pdo->prepare($sql);
    $operation->execute(
        array(
            ':name' => $taskData['name'],
            ':project_id' => $taskData['project_id'],
            ':description' => $taskData['description'],
            ':start_date' => $taskData['start_date'],
            ':end_date' => $taskData['end_date'],
            ':effort' => $taskData['effort'],
            ':status' => $taskData['status'],
            ':priority' => $taskData['priority'],
        )
    );
    return $operation->rowCount() > 0;
}
function addTeamMemberToTask(): bool
{
    global $pdo;
    $taskData = $_SESSION['add_team_member'];
    $sql = 'INSERT INTO Tasks_Users (task_id,user_id,role,contribution,start_date)
        VALUES(:task_id,:user_id,:role,:contribution,:start_date)';
    $operation = $pdo->prepare($sql);
    $operation->execute(
        array(
            ':task_id' => $taskData['task_id'],
            ':user_id' => $taskData['user_id'],
            ':role' => $taskData['role'],
            ':contribution' => $taskData['contribution'],
            ':start_date' => $taskData['start_date']
        )
    );
    return $operation->rowCount() > 0;
}
function getTaskProgress($taskId): int{
    global $pdo;
    $sql = 'SELECT sum(contribution) AS contributionSum FROM Tasks_Users where id = :task_id';

    $operation = $pdo->prepare($sql);
    $operation->execute([':task_id' => $taskId]);
    $result  =$operation->fetch(PDO::FETCH_ASSOC);
    return (int) $result['contributionSum'];

}
function acceptTask($taskId): bool
{
    global $pdo;
    $sql = 'UPDATE Tasks SET status = "In Progress" where id = :taskID';
    $operation = $pdo->prepare($sql);
    $operation->execute(array(':taskID' => $taskId));
    return $operation->rowCount() > 0;
}
function isMemberAssignedTaskAtDate($userID,$startDate,$taskID):mixed{
    global $pdo;    
    $sql = 'SELECT * FROM Tasks_Users WHERE user_id = :userID AND start_date = :startDate AND task_id = :taskID';
    $operation = $pdo->prepare($sql);
    $operation->execute([':userID'=> $userID,'startDate'=> $startDate,':taskID'=> $taskID]);
    return $operation->fetch(PDO::FETCH_ASSOC);

}
function rejectTask($taskID): bool
{
    global $pdo;
    $sql = 'DELETE FROM Tasks_Users where task_id = :task_id AND user_id = :user_id';

    if (!isset($_SESSION['user']['user_id'])) {
        throw new Exception("session is invalid.");
    }

    $operation = $pdo->prepare($sql);
    $operation->execute(array(':task_id' => $taskID, ':user_id' => $_SESSION['user']['user_id']));
    return $operation->rowCount() > 0;
}
function getTasks($projectId)
{
    global $pdo;
    $sql = 'SELECT * FROM Tasks WHERE project_id = :project_id';
    $operation = $pdo->prepare($sql);
    $operation->execute([':project_id' => $projectId]);
    return $operation->fetchAll(PDO::FETCH_ASSOC);

}
function filterTasks($projectId = null, $priority = null, $status = null, $startDate = null, $endDate = null)
{
    global $pdo;
    $sql = 'SELECT *, 0 as completion_percentage FROM Tasks WHERE 1 = 1 ';
    $params = [];
    if ($projectId !== null && !empty($projectId)) {
        $sql .= ' AND project_id = :project_id';
        $params[':project_id'] = $projectId;
    }
    if ($priority !== null && !empty($priority)) {
        $sql .= ' AND priority = :priority';
        $params[':priority'] = $priority;
    }
    if ($status !== null && !empty($status)) {
        $sql .= ' AND status = :status';
        $params[':status'] = $status;
    }
    if ($startDate !== null && !empty($startDate)) {
        $sql .= ' AND start_date >= :start_date';
        $params[':start_date'] = $startDate;
    }
    if ($endDate !== null && !empty($endDate)) {
        $sql .= ' AND end_date <= :end_date';
        $params[':end_date'] = $endDate;
    }
    $operation = $pdo->prepare($sql);
    
    $operation->execute($params);
    return $operation->fetchAll(PDO::FETCH_ASSOC);
}
function getTaskById($taskId)
{
    global $pdo;
    $sql = 'SELECT *, 0 as completion_percentage FROM Tasks WHERE id = :task_id';
    $operation = $pdo->prepare($sql);
    $operation->execute(params: [':task_id' => $taskId]);
    return $operation->fetch(PDO::FETCH_ASSOC);

}
function getUserByID($userId){
    global $pdo;
    $sql = 'SELECT * From Users where user_id = :userID';
    $operation = $pdo->prepare($sql);   
    $operation->execute(params: [':userID'=> $userId]);
    return $operation->fetch(PDO::FETCH_ASSOC);
}

//todo implement search page 15
//todo update progress and status
//todo task details page: 


?>