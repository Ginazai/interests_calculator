<nav class="navbar navbar-expand-lg bg-dark shadow-lg px-5 py-1" data-bs-theme="dark">
  <div class="container-fluid">
      <a class="navbar-brand" href="#"><i class="fa fa-bar-chart" style="font-size:18px"></i> Calculadora de intereses</a>
      <div class="btn-group float-end my-3 m-1" role="group">
        <a class='btn btn-sm btn-secondary' href='php/actions/download_all_csv.php'><i class="fa fa-download" style="font-size:18px"></i> Descargar todo como archivo CSV</a>
        <button class='btn btn-sm btn-success' type='submit' data-bs-toggle='modal' data-bs-target='#add-modal'><i class="fa fa-plus-square" style="font-size:18px"></i> Agregar cuenta</button> 
      </div> 
  </div>
</nav>
<!---------------------------------------------- Add modal ---------------------------------------------->
<div class='modal fade' id='add-modal' tabindex='-1' aria-labelledby='modal-label' aria-hidden='true'>
  <div class='modal-dialog modal-dialog-centered'>
    <div class='modal-content'>
      <div class='modal-header bg-dark'>
        <h1 class='modal-title fs-5 text-white' id='modal-label'>Agregar cuenta por cobrar</h1>
      </div>
      <div class='modal-body'>
        <div class='container-fluid justify-content-center form-signin'>
    <!--------------------------Add Form -------------------------->
          <form id='account-add' class='row g-3 needs-validation' role='form' name='account-add' action='php/actions/add_account.php' method='post' novalidate>


              <div class='form-floating my-3'>
                <input type='text' name='accout_name' id='accout_name' class='form-control form-validate' placeholder='Nombre' val="" required>
                <label for='accout_name'>Nombre de la cuenta</label>
                <div id="name-error" class="text-danger errors"></div>
              </div>
              <div class='form-floating my-0 mb-3'>
                <input type='text' name='borrower' id='borrower' class='form-control form-validate' placeholder='Deudor' required>
                <label for='borrower'>Deudor</label>
                <div id="owner-error" class="text-danger errors"></div>
              </div>

            <div class="my-0 mb-3">  
              <label for="amount_borrowed" class="form-label">Cantidad solicitada</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input id="amount_borrowed" name="amount_borrowed" type="text" class="form-control" aria-label="Amount (to the nearest dollar)" required>
              </div>
              <div id="amount-error" class="text-danger errors"></div>
            </div>

            <div class="my-0 mb-3">  
              <label for="interest_rate" class="form-label">Tasa de interes</label>
              <div class="input-group">
                <input id="interest_rate" name="interest_rate" type="text" class="form-control" aria-label="Interest Rate" required>
                <span class="input-group-text">%</span>
              </div>
              <div id="rate-error" class="text-danger errors"></div>
            </div>

            <div class="my-0 mb-3"> 
              <div class="input-group">
                <label class="input-group-text" for="cycle">Ciclo</label>
                <select class="form-select" id="cycle" name="cycle" required>
                  <option selected disabled value="">Seleccione...</option>
                  <option value="15">Quincenal</option>
                  <option value="30">Mensual</option>
                </select>
              </div>
              <div id="cycle-error" class="text-danger errors"></div>
            </div>

            <div class="my-0 mb-3">
              <div class='form-floating'>
                <input class='form-control' type='date' name='start_date' id='start_date' placeholder='Fecha de inicio' required>
                <label for='start_date'>Fecha de inicio</label>
              </div>
              <div id="date-error" class="text-danger errors"></div>
            </div>
            
          </form>
<!--------------------------Add Form -------------------------->
        </div>
      </div>

      <div class='modal-footer bg-dark'>
        <button type='submit' form='account-add' class='btn btn-success'>Agregar cuenta</button>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!--------------------------Add modal -------------------------->