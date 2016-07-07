<?php
require_once 'dbConnection/connection.php';

//Get Poll Question
$pollsQuery=$db->query("
    SELECT id,question
    FROM polls
    WHERE DATE(Now()) BETWEEN starts AND ends
    ORDER BY id
    LIMIT 1
    
    ");

$poll=$pollsQuery->fetchObject();
$id =$poll->id; 
        

//Get the user answer for this poll
$answerQuery = $db->prepare("
        SELECT polls_choices.id AS choice_id,polls_choices.name AS choice_name
        FROM polls_answers
        JOIN polls_choices
        ON polls_answers.choice=polls_choices.id
        WHERE polls_answers.user = :user
        AND polls_answers.poll = :poll
        ");
    
    $answerQuery ->execute([
        'user' => $_SESSION['user_id'],
        'poll' => $id
    ]);
    
    //echo '<pre>', print_r($answerQuery->fetchObject()),'</pre>';
    
$completed = $answerQuery->rowCount()? true:false;

if($completed){
    //Get all Answers
    $answerQuery= $db->prepare("
            SELECT 
            polls_choices.name,
            COUNT(polls_answers.id) * 100/(
                SELECT COUNT(*)
                FROM polls_answers
                WHERE polls_answers.poll = :poll) AS percentage
            FROM polls_choices
            LEFT JOIN polls_answers
            ON polls_choices.id =polls_answers.choice
            WHERE polls_choices.poll = :poll
            GROUP BY polls_choices.id
            ");
        
        $answerQuery->execute([
            'poll' => $id
    ]);
        
        
        //Extract answers
        while($row=$answerQuery->fetchObject()){
            $answers[]=$row;
        }
    
    
}else{
    //Get Poll Choices

    $choicesQuery = $db->prepare("
                    SELECT polls.id,polls_choices.id As choice_id,polls_choices.name
                    FROM polls
                    JOIN polls_choices
                    ON polls.id = polls_choices.poll
                    WHERE polls.id = :poll
                    AND DATE(NOW()) BETWEEN polls.starts AND polls.ends
                    ");

    $choicesQuery->execute([
                    "poll" => $id
                ]);   

    //print_r($choicesQuery->fetchObject());

    //Extract choices
    while($row=$choicesQuery->fetchObject()){
        $choices[]=$row;
    }
}




?>

<html>
<title>firstwhistle-myclub</title>
<link rel="stylesheet" type="text/css" href="css/main.css">

<body>

 <div id="container">

<!-- HEADER -->

<div id="header">

<p> <a href="index.php" style="text-decoration:none"> Home </p></a>
<p> <a href="" style="text-decoration:none">Contact Us </p></a>
<p> <a href="terms.htm" style="text-decoration:none"> Terms </p></a>



<!--Header icons -->
<img class ="logo" src="images/logo.png" >

<a href="https://twitter.com/firstwhistle" target="_blank"> <img class="socialmedia" src="images/twitter.png"> </a>
<a href="https://goo.gl/4SM1Wg" target="_blank"> <img class="socialmedia" src="images/youtube.png"> </a>
<a href="mailto:webmaster@example.com" target="_blank"> <img class="socialmedia" src="images/mail.png"> </a>


</div>


<!-- LEFTBAR -->
<div id="leftbar">
		
		<!-- TOP BLUE SECTION -->
		<div id="title1"> </div>
			<div id="top"> </div>

		<!-- BOTTOM WHITE SECTION YOU WILL BE WORKING ON-->
                <form action="vote.php" method="POST">
		<div id="title2"> POLL </div>
			<div id="bottom"> 
                            <div class ="poll">
                                <div class="poll-question">
                                    <?php if(!empty($poll)): ?>
                                        <ul>
                                           <?php echo $poll->question;   ?><br></br>
                                            <?php if($completed): ?>
                                                <p>You have completed the poll,Thanks.</p><br></br>
                                                <ul>
                                                    <?php foreach($answers as $answer):?>
                                                        <li><?php echo $answer->name;?>(<?php echo number_format($answer -> percentage,2);?>%)</li><br></br>
                                                    <?php endforeach; ?>
                                                </ul>
                                                
                                                
                                                
                                            <?php else: ?>
                                                    <?php if(!empty($choices)):?>
                                                        <div class="poll-options">
                                                            <div class="poll-option">
                                                               <?php foreach($choices as $index=>$choice):?> 
                                                                    <input type="radio" name="choice" value="<?php echo $choice->choice_id?>" id="c<?php echo $index;?>">
                                                                    <label for="c<?php echo $index; ?>"><?php echo $choice->name; ?></label><br></br>
                                                                <?php endforeach;?>    
                                                            </div>

                                                        </div>
                                                    <?php else: ?>
                                                        <p> There are no choices right now.</p>
                                                    <?php endif; ?>
                                                
                                                        
                                                
                                            </ul>
                                            <input type="submit" value="Submit Answer">
                                            <input type="hidden" name="poll" value="<?php echo $id; ?>">
                                            <?php endif; ?>
                                            
                                        <?php else: ?>
                                            <p> Sorry,no polls available right now.</p>
                                        <?php endif; ?>
                                    </div>


                                        
                                        
                                    
                                </form>
                                
                                
                                
                                
                                
                                
                                
                            </div>
                        
                        
                        
                        
                        
                        </div>



</div>

<!-- MIDDLE -->
<div id="middle">

</div>

<!-- RIGHTBAR -->

<div id="rightbar">

</div>

<!-- FOOTER -->

<div id="footer">
<p style="text-align:center;"> Copyright &copy; FirstWhistle </p>

<ul style="text-align:left;">
	<br>
	<li>Email: firstwhistle@hotmail.com</li><br>
	<li>Angel.co: </li><br>
	<li>Youtube: https://goo.gl/4SM1Wg</li><br>
	<li>Twitter: https://twitter.com/firstwhistle </li><br>
</ul>


</div>

</div>
</body>

</html>