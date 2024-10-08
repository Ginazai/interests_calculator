<?php
session_start();
require_once '../connection.php';
if(isset($_POST)){
  $id=$_GET['id'];
  $get_data=$con->prepare("SELECT * FROM( 
                          SELECT account_id as id,account_name,owner,create_date,null as interests,borrow_amount as debit,null as credit 
                          FROM accounts WHERE account_id=:id
                          UNION 
                          SELECT account_id,null,null,create_date,null,null,amount FROM payments WHERE account_id=:id
                          UNION 
                          SELECT account_id,null,null,create_date,interests_from_payment,null,null FROM payments WHERE account_id=:id) AS csv_data 
                          ORDER BY DATE(create_date) ASC"); 
  
  $get_data->execute([":id"=>$id]);
  $all_data=$get_data->fetchAll(PDO::FETCH_ASSOC);
  $file = fopen('php://output', 'w'); 
  $csv_header=array("Referencia","Fecha","Descripcion","Debito","Credito");
  fputcsv($file, array_values($csv_header));    
  if($all_data>0){
    foreach($all_data as $element){
      $element['account_name']!=NULL? $account_name=$element['account_name'] : "";
      $element['owner']!=NULL? $account_owner=$element['owner'] : "";


      $fecha=date('d/m/Y',strtotime($element['create_date']));
      $descripcion="";
      $referencia=$element['id'];
      $debito=$element['debit'];
      $credito=$element['credit'];
      $intereses=$element['interests'];

      if($debito!=NULL){
        $descripcion="Prestamo de $".$debito." otorgado";
        $credito="";
      }
      else if($credito!=NULL) {
        $descripcion="pago por $".$credito." realizado";
        $debito="";
      }
      else if($intereses!=NULL&&$intereses>0){
        $descripcion="Intereses acomulados en la cuenta";
        $debito=$intereses;
        $credito="";
      } else {continue;}
      
      $csv_output=array($referencia,$fecha,$descripcion,$debito,$credito);
      $fname=date('d-m-Y',strtotime(date_default_timezone_get()))."-".$account_owner."-".$account_name.".csv";
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="'.$fname.'"');
      header('Pragma: no-cache');
      header('Expires: 0');
      fputcsv($file, array_values($csv_output));
    }
  }
  fclose($file);
}  
?>