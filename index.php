<?php
require_once 'php/connection.php';

$data=$con->prepare("SELECT * FROM accounts");
$data->execute();
while($get_data=$data->fetch(PDO::FETCH_ASSOC)){$all_data[]=$get_data;}

$payments=$con->prepare("SELECT * FROM payments");
$payments->execute();
while($payment_row=$payments->fetch(PDO::FETCH_ASSOC)){$all_payments[]=$payment_row;}
$all_payments=json_encode($all_payments, JSON_PRETTY_PRINT);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interests Calculator</title>
    <script 
    src="https://code.jquery.com/jquery-3.7.1.min.js" 
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
    crossorigin="anonymous"></script>
    <script 
    src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" 
    integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" 
    crossorigin="anonymous"></script>
	  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
    crossorigin="anonymous">
    <?= isset($all_payments) ? "<script type='text/javascript'>var all_payments=$all_payments;</script" : "" ?>
  </head>
  <body>
    <div class="container">
      <button class='btn btn-dark my-3 float-end' type='submit' data-bs-toggle='modal' data-bs-target='#add-modal'>+ Agregar</button>
      <div class="row w-100">
    <?php
    if(count($all_data)>0){
      foreach($all_data as $account){
        $id=$account['account_id'];
        $account_name=$account['account_name'];
        $borrow=$account['borrow_amount'];
        $owner=$account['owner'];
        $create_date=date('d-m-Y',strtotime($account['create_date']));
        $active=$account['active'];
        ?>
        <div class="my-2">
          <div class="accordion" id="data-accordion-<?= $id ?>">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $id ?>" aria-expanded="false" aria-controls="collapse-<?= $id ?>">
                  <div class="d-flex justify-content-between w-100">
                    <div class="row">
                      <h3><?= $account_name ?></h3>
                      <p><?= $owner ?></p>
                    </div>
                    <div class="ms-auto me-3 h-100 my-auto">
                      <div>
                        <div class="col-12"><p class="float-end my-0">$<?= $borrow ?></p></div>
                        <div class="col-12"><p class="float-end my-0" ><?= $create_date ?></p></div>
                        <div class="col-12"><b><p class="float-end my-0"><?=$active==1?"active":"inactive"?></p></b></div>
                      </div>
                    </div>                
                  </div>
                </button>
              </h2>
              <div id="collapse-<?= $id ?>" class="accordion-collapse collapse" data-bs-parent="#data-accordion-<?= $id ?>">
                <div class="accordion-body">
                  <button class='btn btn-dark btn-sm' type='submit' data-bs-toggle='modal' data-bs-target='#add-payment-<?=$id?>'>+ Agregar pago</button> 

                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Balance</th>
                        <th scope="col">Intereses</th>
                        <th scope="col">Pago</th>
                        <th scope="col">Nuevo Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $get_history=$con->prepare("SELECT accounts.borrow_amount, payments.*
                                                  FROM accounts
                                                  JOIN payments
                                                  ON accounts.account_id=payments.account_id
                                                  WHERE accounts.account_id=:cid
                                                  ORDER BY DATE(accounts.create_date)");
                      $get_history->execute([':cid'=>$id]);
                      $history=array();
                      while($history_row=$get_history->fetch(PDO::FETCH_ASSOC)){$history[]=$history_row;}
                      if(count($history)>0){
                        $last_amount=$borrow;
                        foreach($history as $element){
                          $date_gap=(intval(date('d',strtotime($element['payment_date'])))-intval(date('d', strtotime($create_date))));
                          $payment_id=$element['payment_id'];
                          $payment_amount=$element['amount'];
                          $payment_date=date('d-m-Y',strtotime($element['payment_date']));

                          // echo "date gap: " . $date_gap . "<br>";
                          ?>
                          <tr>
                            <th scope="row"><?=$payment_id?></th>
                            <td><?=$payment_date?></td>
                            <td><?= $last_amount ?></td>
                            <td><?= $interests = round($last_amount*(0.6/365),2) ?></td>
                            <td><?= $payment_amount ?></td>
                            <td><?=$last_amount-=round($payment_amount+$interests,2)?></td>
                          </tr>
                          <?php
                        }
                      }
                      ?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
    }   
    ?>
      </div>
    </div>
  <script 
  src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
  integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" 
  crossorigin="anonymous"></script>
  <script 
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
  crossorigin="anonymous"></script>
  <!-- my resources -->
  <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
  <script type="text/javascript" src="js/script.js"></script>

  <!---------------------------------------------- Add modal ---------------------------------------------->
  <div class='modal fade' id='add-modal' tabindex='-1' aria-labelledby='modal-label' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
      <div class='modal-content'>
        <div class='modal-header bg-dark'>
          <h1 class='modal-title fs-5 text-white' id='modal-label'>Agregar cuenta</h1>
        </div>
        <div class='modal-body'>
          <div class='container-fluid justify-content-center form-signin'>
      <!--------------------------Add Form -------------------------->
            <form id='user-add' class='row g-3' role='form' name='user-add' action='actions/create/crear_usuario.php' method='post'>

  
                <div class='form-floating'>
                  <input type='text' name='name' id='name' class='form-control' placeholder='Nombre'>
                  <label for='name'>Nombre de la cuenta</label>
                </div>
                <div class='form-floating'>
                  <input type='text' name='lastname' id='lastname' class='form-control' placeholder='Apellido'>
                  <label for='lastname'>Acreedor</label>
                </div>

              <div class='form-floating'>
                <input type='number' name='balance' id='balance' class='form-control' placeholder='Numero de telefono' aria-describedby='phone-label'>
                <label for='balance'>Monto solicitado</label>
              </div>
              <div class='form-floating'>
                <input class='form-control' type='date' name='dob' id='dob' placeholder='Fecha de nacimiento'>
                <label for='dob'>Fecha de inicio</label>
              </div>
              
            </form>
  <!--------------------------Add Form -------------------------->
          </div>
        </div>

        <div class='modal-footer bg-dark'>
          <button type='submit' form='user-add' name='form-add-submit' class='btn btn-info'>Agregar</button>
          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!--------------------------Add modal -------------------------->
  </body>
</html>
