<?php include "../models/transactionModel.php";
$tm = new transactionModel();
$action = isset($_REQUEST['action'])?$_REQUEST['action']:NULL;

$page = isset($_SESSION['page'])?$_SESSION['page']:NULL;

$user= isset($_REQUEST['user'])?$_REQUEST['user']:NULL;

$submit = isset($_REQUEST['submit'])?$_REQUEST['submit']:NULL;

if($submit == 'pay'){
    $payment['value'] = isset($_REQUEST['payment'])?$_REQUEST['payment']:NULL;
    $payment['memberID'] = isset($_REQUEST['memberID'])?$_REQUEST['memberID']:NULL;
    $record = $tm->getrecord($payment['memberID']);
    $current = strtotime(date("Y-m-d"));
    $recdate = strtotime(date_format(new DateTime($record[0]['recDate']),"Y-m-d"));
     
    

}

if($action == 'transact'){
    $transaction['fullid']= isset($_REQUEST['fullid'])?$_REQUEST['fullid']:NULL;
    $transaction['amtpayment']= isset($_REQUEST['amtpayment'])?$_REQUEST['amtpayment']:NULL;
    $transaction['userID']= isset($_REQUEST['userID'])?$_REQUEST['userID']:NULL;

    print_r($transaction);
    // $account = $tm->getaccount($transaction);
    // if($getaccount != null){
    //     $addtransaction = $tm->addtrans($transaction,$account);
    // }
    
}

if($action == 'addmember'){

    $member['fname']= isset($_REQUEST['first-name'])?$_REQUEST['first-name']:NULL;
    $member['lname']= isset($_REQUEST['last-name'])?$_REQUEST['last-name']:NULL;
    $member['mname']= isset($_REQUEST['middle-name'])?$_REQUEST['middle-name']:NULL;
    $member['contact']= isset($_REQUEST['contact-num'])?$_REQUEST['contact-num']:NULL;
    $member['street']= isset($_REQUEST['street'])?$_REQUEST['street']:NULL;
    $member['barangay']= isset($_REQUEST['barangay'])?$_REQUEST['barangay']:NULL;
    $member['province']= isset($_REQUEST['province'])?$_REQUEST['province']:NULL;
    $member['city']= isset($_REQUEST['city'])?$_REQUEST['city']:NULL;
    $member['zcode']= isset($_REQUEST['zcode'])?$_REQUEST['zcode']:NULL;
    $member['country']= isset($_REQUEST['country'])?$_REQUEST['country']:NULL;
    $member['gender']= isset($_REQUEST['gender'])?$_REQUEST['gender']:NULL;
    $member['address'] = $member['street'].",".$member['barangay'].",".$member['province'].",".$member['city'].",".$member['country']." ".$member['zcode'];
    $member['rating']= "0";
    $member['memberID']=substr(md5(uniqid()),0,5);
    $member['amount'] =isset($_REQUEST['amount'])?$_REQUEST['amount']:NULL;

    //add memeber
    $addmember = $tm->addmember($member);
    while($addmember == '1062'){
		$member['memberID']=substr(md5(uniqid()),0,5);
		$addmember = $tm->addmember($member);
		print_r($addmember);
		if($addmember == '101'){
			break;
		}
    }
    //add account
    $account = $tm->calculate($member);
    $account['accID']=substr(md5(uniqid()),0,5);
    $account['memberID'] = $member['memberID'];
    print_r($account);
    $addaccount = $tm->addaccount($account);
    while($addaccount == '1062'){
        $account['accID']=substr(md5(uniqid()),0,5);
        $addccount = $tm->addaccount($account);
        print_r($addaccount);
        if($addaccount == '101'){
            break;
        }
    }
    //add record
    $record['recordID']=substr(md5(uniqid()),0,5);
    $record['description']='Initial Record - New Member';
    $record['accID'] = $account['accID'];
    $record['AccuBal'] = 0;
    $record['AdvBal'] = 0;
    $record['payment'] = 0;
    $addrecord = $tm->addrecord($record);
    while($addrecord == '1062'){
        $record['memberID']=substr(md5(uniqid()),0,5);
        $addrecord = $tm->addrecord($record);
        print_r($addrecord);
        if($addrecord == '101'){
            break;
        }
    }


    if($addmember){
       
    }

}

//Page Conditions

if($page == 'c_lendees.php' OR 'c_home.php'){
    $getmembers = $tm->getmembers();
}





?>