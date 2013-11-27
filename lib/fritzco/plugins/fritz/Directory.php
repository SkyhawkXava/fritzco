<?php

namespace fritzco\plugins\fritz;

use fritzco\base\BaseDirectory;
use fritzco\base\BaseContact;
use fritzco\base\BaseNumber;
use fritzco\base\NumberType;
use fritzco\base\BaseEmail;
use fritzco\base\EmailType;

class Directory extends BaseDirectory{
    public static $number_type_mapping = array("home" => NumberType::HOME,
                                        "mobile" => NumberType::MOBILE,
                                        "work" => NumberType::WORK,
                                        "fax" => NumberType::FAX,
                                        "fax_work" => NumberType::FAX_WORK,
                                        "other" => NumberType::OTHER);
    public static $email_type_mapping = array(
                                        "private" => EmailType::PRIVATE_,
                                        "business" => EmailType::BUSINESS,
                                        "other" => EmailType::OTHER);
                                        

    public static function fromXML($xml, $id){
        $directory = new Directory($id);
        
        $attributes = $xml->phonebook->attributes();
        $directory->setName((string)$attributes["name"]);
        
        for ($i = 0; $i < count($xml->phonebook->contact); ++$i){ 
            $contact = new BaseContact($i);
            $contact->setDisplayName((string)$xml->phonebook->contact[$i]->person->realName);
            
            for ($j = 0; $j < count($xml->phonebook->contact[$i]->telephony->number); ++$j){
                $number = new BaseNumber();
                $attributes = $xml->phonebook->contact[$i]->telephony->number[$j]->attributes();
                $raw_number = preg_replace('/[^0-9+]/', '', $xml->phonebook->contact[$i]->telephony->number[$i]);
                $number->setNumber($raw_number);
                $raw_type = (string) $attributes["type"];
                $type = NumberType::NONE;
                $other_type = NULL;
                if(array_key_exists($raw_type, self::$number_type_mapping)){
                    $type = self::$number_type_mapping[$raw_type];
                }
                else if(strstr($raw_type,"label:")){
                    $type = NumberType::OTHER;
                    $other_type = substr($raw_type,strlen("label:"));
                }
                $number->setType($type, $other_type);
                $contact->addNumber($number);
            }
            
            for ($j = 0; $j < count($xml->phonebook->contact[$i]->services->email); ++$j){
                $email = new BaseEmail();
                $attributes = $xml->phonebook->contact[$i]->services->email[$j]->attributes();
                $raw_email = (string)$xml->phonebook->contact[$i]->services->email[$j];
                $email->setAddress($raw_email);
                $raw_type = (string) $attributes["classifier"];
                if(array_key_exists($raw_type, self::$email_type_mapping)){
                    $type = self::$email_type_mapping[$raw_type];
                }
                else if(strstr($raw_type,"label:")){
                    $type = EmailType::OTHER;
                    $other_type = substr($raw_type,strlen("label:"));
                }
                $email->setType($type, $other_type);
                $contact->addEmail($email);
            }
            
            $directory->addContact($contact);
        }
        
        return $directory;
    }
}

?>
