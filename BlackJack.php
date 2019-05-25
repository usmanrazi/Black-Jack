<?php
class Black_Jack
{	
	function __construct() 
	{
		session_start();
		if(isset($_SESSION['blackJack'])){
			if($_SESSION['blackJack']['gameState'] == 'completed'){
				unset($_SESSION['blackJack']);
			}
			else if($_POST['action'] == 'start'){
				for($i=0;$i<2;$i++){
					echo $_SESSION['blackJack']['dealerCards'][$i].'<br>';
				}
				for($i=0;$i<count($_SESSION['blackJack']['userCards']);$i++){
					echo $_SESSION['blackJack']['userCards'][$i].'<br>';
				}
				echo $_SESSION['blackJack']['userScore'].'<br>' ;
				echo $_SESSION['blackJackStats']['wins'] .'<br>';
				echo $_SESSION['blackJackStats']['lose'] . '<br>';
				echo $_SESSION['blackJackStats']['draws']. '<br>' ;
			}
			else if($_POST['action'] == 'reset'){
				unset($_SESSION['blackJack']);
			}
		}
		if(!isset($_SESSION['blackJack']) && $_POST['action'] != 'getScore' && $_POST['action'] != 'gamestates' ) {
			$_SESSION['blackJack']['availableCards'] = array(
				'0' => array(
					'A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'
				),
				'1' => array(
					'A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'
				),
				'2' => array(
					'A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'
				),
				'3' => array(
					'A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'
				),
			);
			$_SESSION['blackJack']['userScore'] = 0 ;
			$_SESSION['blackJack']['dealerScore'] = 0 ;
			$_SESSION['blackJack']['stick'] = true;	
			$_SESSION['blackJack']['hit'] = true;
			if(!isset($_SESSION['blackJackStats']))
			{
				$_SESSION['blackJackStats']['wins'] = 0;
				$_SESSION['blackJackStats']['lose'] = 0;
				$_SESSION['blackJackStats']['draws']=0 ;
				$_SESSION['blackJackStats']['totalGames'] = 0 ;
				$_SESSION['blackJackStats']['gamesData'] = array();
			}
			$_SESSION['blackJack']['gameState'] = 'uncompleted';
			$this->startingHits();
		}
		else if($_POST['action'] == 'gamestates')
		{
			if(isset($_SESSION['blackJackStats']))
			{
				echo $_SESSION['blackJackStats']['wins'] .'<br>';
				echo $_SESSION['blackJackStats']['lose'] . '<br>';
				echo $_SESSION['blackJackStats']['draws']. '<br>' ;
			}
			else
			{
				echo '0'.'<br>';
				echo '0'.'<br>';
				echo '0'.'<br>';
			}
		}
	}
	function getScores()
	{
		for($i=0 ; $i < count($_SESSION['blackJackStats']['gamesData']); $i++){
			echo $_SESSION['blackJackStats']['gamesData'][$i].'<br>';
		}
	}
	function cardScore($cardNum)
	{
		$cards = array(
			'0' => array(
				'A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'
			),
			'1' => array(
				'A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'
			),
			'2' => array(
				'A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'
			),
			'3' => array(
				'A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'
			),
		);
		switch($cards['0'][$cardNum]){
			case 'A':
				return 11;
				break;
			case 'J':
			case 'Q':
			case 'K':
				return 10;
				break;
			default:
				return $cards['0'][$cardNum];
				break;
		}
	}
	function startingHits()
	{
		//dealers starting cards
		$_SESSION['blackJack']['dealerCards'] = array();
		for($i=0;$i<2;$i++){
			$deckColour = rand(0,3);
			$deckNumber = rand(0,(count($_SESSION['blackJack']['availableCards'][$deckColour])-1));
			while($_SESSION['blackJack']['availableCards'][$deckColour][$deckNumber] == NULL){
				$deckNumber = rand(0,(count($_SESSION['blackJack']['availableCards'][$deckColour])-1));
			}
			$pickedCard = $deckColour . '-' . $deckNumber ;
			echo $pickedCard .'<br>' ;
			array_push($_SESSION['blackJack']['dealerCards'],$pickedCard);
			$_SESSION['blackJack']['availableCards'][$deckColour][$deckNumber] = NULL;
			$_SESSION['blackJack']['dealerScore'] += $this->cardScore($deckNumber);
		}
		// user starting cards
		$_SESSION['blackJack']['userCards'] = array();
		for($i=0;$i<2;$i++){
			$deckColour = rand(0,3);
			$deckNumber = rand(0,(count($_SESSION['blackJack']['availableCards'][$deckColour])-1));
			while($_SESSION['blackJack']['availableCards'][$deckColour][$deckNumber] == NULL){
				$deckNumber = rand(0,(count($_SESSION['blackJack']['availableCards'][$deckColour])-1));
			}
			$pickedCard = $deckColour . '-' . $deckNumber ;
			array_push($_SESSION['blackJack']['userCards'],$pickedCard);
			$_SESSION['blackJack']['availableCards'][$deckColour][$deckNumber] = NULL;
			$_SESSION['blackJack']['userScore'] += $this->cardScore($deckNumber);
			echo $pickedCard .'<br>' ;
		}
		echo $_SESSION['blackJack']['userScore'].'<br>' ;
		echo $_SESSION['blackJackStats']['wins'] .'<br>';
		echo $_SESSION['blackJackStats']['lose'] . '<br>';
		echo $_SESSION['blackJackStats']['draws']. '<br>' ;
		$_SESSION['blackJackStats']['totalGames'] ++;
	}
	function hitUser()
	{
		if($_SESSION['blackJack']['hit'] != false){
			$deckColour = rand(0,3);
			while(count($_SESSION['blackJack']['availableCards'][$deckColour]) == 0) {
				$num1 = rand(0,3);
			}
			$deckNumber = rand(0,(count($_SESSION['blackJack']['availableCards'][$deckColour])-1));
			while($_SESSION['blackJack']['availableCards'][$deckColour][$deckNumber] == NULL){
				$deckNumber = rand(0,(count($_SESSION['blackJack']['availableCards'][$deckColour])-1));
			}
			$pickedCard = $deckColour . '-' . $deckNumber ;
			echo $pickedCard.'<br>';
			array_push($_SESSION['blackJack']['userCards'],$pickedCard);
			$_SESSION['blackJack']['availableCards'][$deckColour][$deckNumber] = NULL;
			$_SESSION['blackJack']['userScore'] += $this->cardScore($deckNumber);
			echo $_SESSION['blackJack']['userScore'].'<br>' ;
			if($_SESSION['blackJack']['userScore'] > 21)
			{
				echo $_SESSION['blackJack']['dealerCards'][(count($_SESSION['blackJack']['dealerCards'])-1)].'<br>';
				echo $_SESSION['blackJack']['dealerScore'].'<br>' ;
				echo 'You Lose!';
				$_SESSION['blackJackStats']['lose']++;
				$_SESSION['blackJack']['gameState'] = 'completed';
				$gameData = $_SESSION['blackJackStats']['totalGames'] . '-' . $_SESSION['blackJack']['userScore'] . '-';
				$gameData .= $_SESSION['blackJack']['dealerScore'] . '-' . 'You Lose!';
				array_push($_SESSION['blackJackStats']['gamesData'],$gameData);
			}
		}
	}
	function stick()
	{
		if($_SESSION['blackJack']['stick'] != false){
			$_SESSION['blackJack']['stick'] = false;	
			$_SESSION['blackJack']['hit'] = false;
			if($_SESSION['blackJack']['userScore'] > 21){
				echo 'winigState'.'<br>';
				echo $_SESSION['blackJack']['dealerCards'][(count($_SESSION['blackJack']['dealerCards'])-1)].'<br>';
				echo $_SESSION['blackJack']['dealerScore'].'<br>' ;
			}
			else{
				echo 'otherState'.'<br>';
				echo $_SESSION['blackJack']['dealerCards'][(count($_SESSION['blackJack']['dealerCards'])-1)].'<br>';
				while($_SESSION['blackJack']['dealerScore'] < 17){
					echo 'again'.'<br>';
					$deckColour = rand(0,3);
					while(count($_SESSION['blackJack']['availableCards'][$deckColour]) == 0) {
						$num1 = rand(0,3);
					}
					$deckNumber = rand(0,(count($_SESSION['blackJack']['availableCards'][$deckColour])-1));
					while($_SESSION['blackJack']['availableCards'][$deckColour][$deckNumber] == NULL){
						$deckNumber = rand(0,(count($_SESSION['blackJack']['availableCards'][$deckColour])-1));
					}
					$pickedCard = $deckColour . '-' . $deckNumber ;
					echo $pickedCard.'<br>';
					$_SESSION['blackJack']['dealerScore'] += $this->cardScore($deckNumber);
					$_SESSION['blackJack']['availableCards'][$deckColour][$deckNumber] = NULL;
				}
				echo $_SESSION['blackJack']['dealerScore'].'<br>' ;
				if($_SESSION['blackJack']['dealerScore'] > $_SESSION['blackJack']['userScore'] && $_SESSION['blackJack']['dealerScore'] <=21){
					$_SESSION['blackJackStats']['lose'] ++;
					$_SESSION['blackJack']['gameState'] = 'completed';
					echo 'You Lose!';
					$gameData = $_SESSION['blackJackStats']['totalGames'] . '-' . $_SESSION['blackJack']['userScore'] . '-';
					$gameData .= $_SESSION['blackJack']['dealerScore'] . '-' . 'You Lose!';
					array_push($_SESSION['blackJackStats']['gamesData'],$gameData);
				}
				else if($_SESSION['blackJack']['dealerScore'] == $_SESSION['blackJack']['userScore'])
				{
					$_SESSION['blackJackStats']['draws'] ++;
					
					$_SESSION['blackJack']['gameState'] = 'completed';
					echo 'Draw!';
					$gameData = $_SESSION['blackJackStats']['totalGames'] . '-' . $_SESSION['blackJack']['userScore'] . '-';
					$gameData .= $_SESSION['blackJack']['dealerScore'] . '-' . 'Draw!';
					array_push($_SESSION['blackJackStats']['gamesData'],$gameData);
				}
				else
				{
					$_SESSION['blackJackStats']['wins']++;
					$_SESSION['blackJack']['gameState'] = 'completed';
					echo 'You Win!';
					$gameData = $_SESSION['blackJackStats']['totalGames'] . '-' . $_SESSION['blackJack']['userScore'] . '-';
					$gameData .= $_SESSION['blackJack']['dealerScore'] . '-' . 'You Win!';
					array_push($_SESSION['blackJackStats']['gamesData'],$gameData);
				}
			}
		}
		
	}
};
$blackJack_obj = new Black_Jack;
if($_POST['action']== 'twist'){
		$blackJack_obj->hitUser();
}
else if($_POST['action']== 'stick'){
		$blackJack_obj->stick();
}
else if($_POST['action']== 'getScore'){
		$blackJack_obj->getScores();
}

?>