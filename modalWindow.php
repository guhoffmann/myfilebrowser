<!-- ******************************************************* -->
<!-- Bootstrap Modal Window used for all messages in the app -->

<div class="modal fade" id="ModalMessage" tabindex="10" role="dialog" aria-labelledby="ModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="ModalTitle">Nachricht</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div> <!-- modal-header -->

			<!-- section for value input -->
			<input class="hidden" type="text" id="inputval" name="foldername" value="">
			<!-- section for editable text -->
			<textarea class="hidden" id="edittext" rows="5" name="edittext" value=""></textarea>
			<!-- section for the upload -->
			<form id="upload" action="cgi-bin/actions.php" method="post" enctype="multipart/form-data"> 
				<input type="file" name="file[]" id="fileinput" class="inputfile" data-multiple-caption="{count} Dateien hochladen" multiple="multiple"/>
				<!-- label MUST follow file input immediately to work! -->
				<label id="filebuttlabel" class="filebuttlabel" for="fileinput">Datei(en) ausw&auml;hlen...</label>
				<!-- hidden value for upload directory -->
				<input id="uploadDir" type="hidden" value="path to upload" name="uploadDir" />
				<input id="action" type="hidden" value="uploadPost" name="action" />
			</form>

			<!-- section for modal window content -->
			<div class="modal-body" id="ModalContent">
			</div>

			<!-- section for the modal window footer -->
			<div class="modal-footer">
				<button type="button" id="ModalOk" class="material-icons btn btn-primary" data-dismiss="modal">done</button>
				<button type="button" id="ModalClose" class="material-icons btn btn-primary" data-dismiss="modal">clear</button>
			</div>
			
		</div> <!-- modal content -->
	</div> <!-- class="modal-dialog modal-dialog-centered" role="document" -->
</div> <!-- class="modal fade" ... -->

