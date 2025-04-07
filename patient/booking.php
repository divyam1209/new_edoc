<?php
session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
        exit();
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit();
}

// Import database
include("../connection.php");

// Get patient details
$sqlmain = "SELECT * FROM patient WHERE pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$userfetch = $result->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Sessions</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Menu -->
        <!-- Your unchanged menu code here... -->

        <div class="dash-body">
            <table width="100%" style="border-spacing: 0; margin: 0; padding: 0; margin-top: 25px; border-collapse: collapse;">
                <tr>
                    <td width="13%">
                        <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding: 11px 25px; margin-left: 20px;">Back</button></a>
                    </td>
                    <td>
                        <form action="schedule.php" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors">&nbsp;&nbsp;

                            <datalist id="doctors">
                                <?php
                                $list11 = $database->query("SELECT DISTINCT docname FROM doctor;");
                                $list12 = $database->query("SELECT DISTINCT title FROM schedule;");

                                while ($row = $list11->fetch_assoc()) {
                                    echo "<option value='{$row["docname"]}'>";
                                }

                                while ($row = $list12->fetch_assoc()) {
                                    echo "<option value='{$row["title"]}'>";
                                }
                                ?>
                            </datalist>

                            <input type="submit" value="Search" class="login-btn btn-primary btn" style="padding: 10px 25px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px; color: rgb(119, 119, 119); margin: 0; text-align: right;">Today's Date</p>
                        <p class="heading-sub12" style="margin: 0;"><?php echo $today; ?></p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex; justify-content: center; align-items: center;">
                            <img src="../img/calendar.svg" width="100%">
                        </button>
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        <div class="centered-content">
                            <div class="abc scroll">
                                <table width="100%" class="sub-table scrolldown" style="padding: 50px; border-collapse: collapse;">
                                    <tbody>
                                        <?php
                                        if (isset($_GET["id"])) {
                                            $id = $_GET["id"];

                                            $sqlmain = "SELECT * FROM schedule 
                                                        INNER JOIN doctor ON schedule.docid = doctor.docid 
                                                        WHERE schedule.scheduleid = ? 
                                                        ORDER BY schedule.scheduledate DESC";

                                            $stmt = $database->prepare($sqlmain);
                                            $stmt->bind_param("i", $id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($row = $result->fetch_assoc()) {
                                                $scheduleid = $row["scheduleid"];
                                                $title = $row["title"];
                                                $docname = $row["docname"];
                                                $docemail = $row["docemail"];
                                                $scheduledate = $row["scheduledate"];
                                                $scheduletime = $row["scheduletime"];

                                                $result12 = $database->query("SELECT * FROM appointment WHERE scheduleid = $id");
                                                $apponum = ($result12->num_rows) + 1;

                                                echo '
                                                <form action="booking-complete.php" method="post">
                                                    <input type="hidden" name="scheduleid" value="' . $scheduleid . '">
                                                    <input type="hidden" name="apponum" value="' . $apponum . '">
                                                    <input type="hidden" name="date" value="' . $today . '">

                                                    <tr>
                                                        <td style="width: 50%;" rowspan="2">
                                                            <div class="dashboard-items search-items">
                                                                <div style="width:100%">
                                                                    <div class="h1-search" style="font-size:25px;">
                                                                        Session Details
                                                                    </div><br><br>
                                                                    <div class="h3-search" style="font-size:18px;line-height:30px">
                                                                        Doctor name: &nbsp;&nbsp;<b>' . $docname . '</b><br>
                                                                        Doctor Email: &nbsp;&nbsp;<b>' . $docemail . '</b>
                                                                    </div><br>
                                                                    <div class="h3-search" style="font-size:18px;">
                                                                        Session Title: ' . $title . '<br>
                                                                        Session Scheduled Date: ' . $scheduledate . '<br>
                                                                        Session Starts: ' . $scheduletime . '<br>
                                                                        Channeling fee: <b>LKR 2,000.00</b>
                                                                    </div><br>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="width: 25%;">
                                                            <div class="dashboard-items search-items">
                                                                <div style="width:100%; padding: 15px 0;">
                                                                    <div class="h1-search" style="font-size:20px; text-align:center;">
                                                                        Your Appointment Number
                                                                    </div>
                                                                    <center>
                                                                        <div class="dashboard-icons" style="width:90%; font-size:70px; font-weight:800; text-align:center; color:var(--btnnictext); background-color: var(--btnice)">
                                                                            ' . $apponum . '
                                                                        </div>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="submit" class="login-btn btn-primary btn btn-book" style="margin-left:10px; padding: 10px 25px; width:95%; text-align:center;" value="Book now" name="booknow">
                                                        </td>
                                                    </tr>
                                                </form>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
