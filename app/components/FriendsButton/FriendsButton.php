<?php
namespace Components;
use Model\Friends;
/**
 * Description of FrindsButton
 *
 * @author Skalda
 */
class FriendsButton extends \Nette\Application\UI\Control {

    /**
     *
     * @var \Model\Friends
     */
    public $friends;
    
    public $from;
    public $to;
    
    
    public function injectFriends(\Model\Friends $friends) {
	$this->friends = $friends;
    }
    
    public function setFrom($id) {
	$this->from = $id;
    }
    
    public function setTo($id) {
	$this->to = $id;
    }
    
    /**
     * (non-phpDoc)
     *
     * @see Nette\Application\Control#render()
     */
    public function render() {
	if($this->to != $this->from) {
//	    $this->template->setTranslator(\Nette\Environment::getService('Nette\ITranslator'));
	    $status = $this->friends->getFriendState($this->from, $this->to);
	    switch($status) {
		case Friends::ACCEPTED:
		    $filename = "alreadyFriend";
		    break;
		case Friends::PENDING_FROM:
		    $filename = "pending";
		    break;
		case Friends::PENDING_TO:
		    $filename = "acceptFriend";
		    break;
		default:
		    $filename = "addFriend";
		    break;
	    }
	    $this->template->setFile(dirname(__FILE__) . '/'.$filename.'.latte');
	    $this->template->render();
	}
    }
    
    public function handleAdd() {
	try {
	    $this->friends->addFriend($this->from, $this->to);
	    $this->flashMessage('Žádost o přátelství přijata', 'info');
	}catch(Model\FriendRequestAlreadyPendingException $e) {
	    $this->flashMessage('Žádost o přátelství již je poslána', 'error');
	}
	$this->redirect('this');
    }
    
    public function handleAccept() {
	try{
	    $this->friends->acceptFriend($this->from, $this->to);
	    $this->flashMessage('Žádost o přátelství byl potvrzena', 'success');
	}catch(Model\FriendRequestDoesNotExistException $e) {
	    $this->flashMessage('Žádost o přátelství neexistuje', 'error');
	}
	$this->redirect('this');
    }
    
    public function handleRemove() {
	try{
	    $this->friends->removeFriend($this->from, $this->to);
	    $this->flashMessage('Přátelství bylo zrušeno', 'success');
	} catch(\Model\FriendRequestDoesNotExistException $e) {
	    $this->flashMessage('Žádost o přátelství neexistuje', 'error');
	}
	$this->redirect('this');
    }

}