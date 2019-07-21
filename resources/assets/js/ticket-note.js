$(document).ready(function () {
    $('.addNote').on('click',function (e) {
        let modal = $('#ReplyModal');
        modal.find('.modal-title').html('Add Note - Ticket#' + ticket.id);
        modal.find('button[type=submit]').html('<i class="fa fa-save"></i> Add Note');
        let form = modal.closest('form').attr('action','note/'+ticket.id);
        tinyMCE.activeEditor.setContent('');
        $('#display_to_requester').attr('checked', note.display_to_requester==1 ? true : false);
        $('#email_to_technician').attr('checked', note.email_to_technician==1 ? true : false);
        $('#as_first_response').parent().show();
        $('#as_first_response').attr('checked', note.as_first_response==1 ? true : false);
    });
});