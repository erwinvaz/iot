<?php

include($_SERVER["DOCUMENT_ROOT"] . "/palco/view/topo.php");

$daoBoard = new BoardDAO();

?>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/palco/view/header.php"); ?>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/menu/view/nav.php"); ?>
<script
  src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
  crossorigin="anonymous"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<div class="content-wrapper">

	<section class="content-header">
		<h1>
			Trevo ME
			<small><?= Traducao::t('Board'); ?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"><?= Traducao::t('Board'); ?></a></li>
			<li class="active"><?= Traducao::t('Gerenciamento'); ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">

		<!--Grid row-->
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?= Traducao::t('Gerenciamento de Board'); ?></h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->


					<?php
					$objBoards = $daoBoard->listarAllBoards($id_pessoa, $board);
					$keys = array_keys($objBoards);
					$size = count($objBoards);

					if ($size == 0) {
					} else {
						$html_buttons = null;
						for ($i = 0; $i < $size; $i++) {
							$key   = $keys[$i];
							$id_comp_board = $objBoards[$key]['id_comp_board'];

							if ($objBoards[$key]['state'] == "1") {
								$button_checked = "<input type='checkbox' checked data-toggle='toggle' data-on='Ligado' data-off='Desligado' data-onstyle='success' data-offstyle='danger' name='flip' id='$id_comp_board' onchange='updateOutput(this)'>";
								//$button_checked = "<option value='on' selected>On</option><option value='off'>Off</option>";
							} else {
								$button_checked = "<input type='checkbox' data-toggle='toggle' data-on='Ligado' data-off='Desligado' data-onstyle='success' data-offstyle='danger' name='flip' id='$id_comp_board' onchange='updateOutput(this)'>";
								//$button_checked = "<option value='off' selected>Off</option><option value='on'>On</option>";
							}
							$html_buttons .= '<div align="center" id="containing-element' . $objBoards[$key]['id_comp_board'] . '"><h3><label for="flip">' . $objBoards[$key]['componente'] . ' - Board '
								. $objBoards[$key]['nome_board'] . ' - GPIO ' . $objBoards[$key]['gpio']
								. '(<i><a onclick="deleteOutput(this)" href="javascript:void(0);" id="' . $objBoards[$key]['id_comp_board'] . '">Delete</a></i>)</h3></label>'
								. $button_checked . '</div>';
						}
					}
					echo $html_buttons;
					?>
					<form id="cad_componentes" method="post" action="../controller/salva_componentes.php">
						<div class="box-body">
							<div class="form-group">
								<label for="select_board"><?= Traducao::t('Boards'); ?></label>
								<select class="form-control" id="select_board" name="select_board">
									<?php

									$arrayComboBoard = $daoBoard->montaBoards($id_pessoa);

									$keys = array_keys($arrayComboBoard);
									$size = count($arrayComboBoard);

									for ($i = 0; $i < $size; $i++) {

										$key   = $keys[$i];
										$id_board = $arrayComboBoard[$key]["id_elemento"];
										$descricao = $arrayComboBoard[$key]["descricao"];

									?>
										<option value="<?= $id_board; ?>"><?= Traducao::t($descricao); ?></option>
									<?php

									}

									?>
								</select>
							</div>
							<div class="form-group">
								<label for="componente"><?= Traducao::t('Componente'); ?></label>
								<input type="text" class="form-control" name="componente" id="componente" placeholder="<?= Traducao::t('Componente'); ?>" value="">

							</div>
							<div class="form-group">
								<label for="Gpio"><?= Traducao::t('GPIO Number'); ?></label>
								<input type="number" class="form-control" name="Gpio" id="Gpio" min="0" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="state"><?= Traducao::t('Initial GPIO State'); ?></label>
								<select class="form-control" id="state" name="state">

									<option value="0">0 = OFF</option>
									<option value="1">1 = ON</option>

								</select>
							</div>
						</div>
						<!-- /.card-body -->
						<div class="form-group">
							<div class="row justify-content-center">
								<button class="btn btn-primary" type="button" id="loading" style="display: none;">
									<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
									Loading...
								</button>
								<div id="ohsnap"></div>
							</div>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-primary"><?= Traducao::t('Cadastrar'); ?></button>
						</div>
					</form>
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col md 6 -->
		</div>
		<!-- /.row -->

	</section>
</div><!-- wrapper -->
<?php include($_SERVER["DOCUMENT_ROOT"] . "/palco/view/footer.php"); ?>

<script>
	function updateOutput(element) {

		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == XMLHttpRequest.DONE) {
				console.log(xhr.responseText);
			}
		}
		if (element.checked === true) {
			xhr.open("GET", "../controller/action.php?action=update&id=" + element.id + "&state=1", true);
		} else {
			xhr.open("GET", "../controller/action.php?action=update&id=" + element.id + "&state=0", true);
		}
		xhr.send();
	}

	function deleteOutput(element) {
		var result = confirm("Want to delete this output?");
		if (result) {
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function() {
				if (xhr.readyState == XMLHttpRequest.DONE) {
					console.log(xhr.responseText);
				}
			}
			xhr.open("GET", "../controller/action.php?action=delete&id=" + element.id, true);
			xhr.send();
			$("#containing-element" + element.id).remove();
		}
	}
</script>
<script>
	$.validator.addMethod("valueNotEquals", function(value, element, arg) {
		return arg != value;
	}, "Value must not equal arg.");
	$("#cad_componentes").validate({
		rules: {
			componente: {
				required: true,
				maxlength: 300
			},
			Gpio: {
				required: true
			},
			select_board: {
				valueNotEquals: "0"
			}
		},
		messages: {
			componente: {
				required: "<?= Traducao::t('Informe o Componente!'); ?>"
			},
			select_board: {
				valueNotEquals: "<?= Traducao::t('Informe a Board!'); ?>"
			},
			Gpio: {
				required: "<?= Traducao::t('Informe o numero do GPIO!'); ?>"
			}
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent().prev());
		},
		submitHandler: function(form) {

			$("#loading").show();
			// setup some local variables
			var $form = $(form);
			// let's select and cache all the fields
			var $inputs = $form.find("input, select, button, textarea");
			// serialize the data in the form
			var serializedData = $form.serialize();

			// let's disable the inputs for the duration of the ajax request
			$inputs.prop("disabled", true);

			// fire off the request to /form.php

			request = $.ajax({
				url: "../controller/salva_componentes.php",
				type: "post",
				data: serializedData
			});

			// callback handler that will be called on success
			request.done(function(response, textStatus, jqXHR) {
				// log a message to the console

				$("#loading").hide();
				ohSnap('Ahh Yeah! ' + response, {
					'color': 'green'
				});
				$("#cad_componentes")[0].reset();
				setTimeout(() => {
					ohSnap('Hold on! going updating in two second..', {
						'color': 'green'
					});
				}, 500);
				setTimeout(() => {
					window.location.href = "cadastra_componentes.php";
				}, 2000);


			});

			// callback handler that will be called on failure
			request.fail(function(jqXHR, textStatus, errorThrown) {
				// log the error to the console
				$("#loading").hide();
				ohSnap('Ohh no! ' + textStatus, {
					'color': 'red'
				});

			});

			// callback handler that will be called regardless
			// if the request failed or succeeded
			request.always(function() {
				// reenable the inputs
				$inputs.prop("disabled", false);
			});

		}
	});
</script>
</div><!-- /page -->
</body>

</html>