<?php

include($_SERVER["DOCUMENT_ROOT"] . "/palco/view/topo.php");

?>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/palco/view/header.php"); ?>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/menu/view/nav.php"); ?>

<div class="content-wrapper">

	<section class="content-header">
		<h1>
			Trevo ME
			<small><?= Traducao::t('Boards'); ?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"><?= Traducao::t('Boards'); ?></a></li>
			<li class="active"><?= Traducao::t('Cadastrar'); ?></li>
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
						<h3 class="box-title"><?= Traducao::t('Cadastrar Boards'); ?></h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->

					<form id="cad_boards" method="post" action="">
						<div class="box-body">

							<div class="form-group">
								<label for="nome_board"><?= Traducao::t('Nome da Board'); ?></label>
								<input type="text" class="form-control" name="nome_board" id="nome_board" placeholder="<?= Traducao::t('Nome da Booard'); ?>" value="">
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
	$("#loading").hide();
	//$("#cadastraCusto").submit(function(event){
	// cancels the form submission
	//	event.preventDefault();
	//	submitForm();
	//});
	function submitForm() {
		
		$("#loading").show();
		var language = "<?= $_COOKIE['language']; ?>";
		// Initiate Variables With Form Content
		var $form = $("#cad_boards");
		// let's select and cache all the fields
		var $inputs = $form.find("input, select, button, textarea");
		// serialize the data in the form
		var serializedData = $form.serialize();

		$.ajax({
			type: "POST",
			url: "../controller/salva_boards.php",
			data: serializedData,
			success: function(resposta) {

				var jsonResposta = JSON.parse(resposta);

				if (jsonResposta[0].status == "0") {

					$("#loading").hide();
					ohSnap('Ahh Yeah! ' + jsonResposta[0].mensagem, {
						'color': 'green'
					});
					$("#cad_boards")[0].reset();

				} else {

					$("#loading").hide();
					ohSnap('Ohh no! ' + jsonResposta[0].mensagem, {
						'color': 'red'
					});

				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {

				$("#loading").hide();
				ohSnap('Ohh no! ' + jsonResposta[0].mensagem, {
					'color': 'red'
				});

			}
		});

	}
	$(function() {
		
		$.validator.setDefaults({
			submitHandler: function() {
				
				submitForm();

			}
		});
		$('#cad_boards').validate({
			rules: {
					nome_board: {
						required: true
					}
				},
				messages: {
					nome_board: {
						required: "<?= Traducao::t('Informação obrigatória.'); ?>"
					}
				},
			errorElement: 'span',
			errorPlacement: function(error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group').append(error);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass('is-invalid');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
			}
		});
	});
	

</script>

</html>