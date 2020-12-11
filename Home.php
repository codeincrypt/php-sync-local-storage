<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
  // SYNC LOCAL STORAGE CART TO USER CART 
	function updateusercart(){
		$sessionid = $this->session->userdata('user');
		$data['usr'] = $this->master->get_id('user', ['userId' => $sessionid]);
		$accountid = $data['usr']['user_accountid'];
		//$accountid = "30031120";

		if(isset($_SESSION["lcart"])){
			foreach($_SESSION["lcart"] as $item){ 
				$prodid = $item["prodid"];
				$proatt = $item["proatt"];
				$quanti = $item["quanti"];

				$data['datas'] = $this->master->get_id('usercart', ['productattid' => $proatt, 'userid' => $accountid]);
				$oldquantity = $data['datas']['quantity'];
				$productattid = $data['datas']['productattid'];

				if($proatt == $productattid){
					$quantity = $oldquantity+$quanti;
					$fields = [
						'quantity'		=> $quantity,
						'wishlist'		=> '0',
					];
					$this->master->updateData('usercart',$fields,['productattid' => $proatt,'userid' => $accountid]);
				}
				else {
					$data['datasa'] = $this->master->get_id('productattributes', ['id' => $proatt]);
					$sessids = $data['datasa']['sessid'];

					$data['merchantdata'] = $this->master->get_id('productlist', ['id' => $prodid]);
					$merchantid = $data['merchantdata']['merchant'];

					$fields = [
						'productid'		=> $prodid,
						'productattid'	=> $proatt,
						'quantity'		=> $quanti,
						'sessid'		=> $sessids,
						'wishlist'		=> '0',
						'userid'		=> $accountid,
						'merchantid'	=> $merchantid,
					];
					$this->master->insertData('usercart',$fields);
				}
			}
			unset($_SESSION["lcart"]);
		}
	}
  }
