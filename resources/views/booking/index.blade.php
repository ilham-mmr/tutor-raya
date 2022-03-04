@extends('layouts.base')

@section('additional_css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- dropzonejs -->
<link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/min/dropzone.min.css') }}">
@endsection

@section('title')
Booked Sessions
@endsection

@section('breadcrumb_link')
@endsection

@section('main-content')
{{-- @if ($errors->any()) --}}
<div class="alert alert-danger alert-dismissible fade" id="validationErrorsAlert" role="alert">
    <ul id="validationErrors">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
{{-- @endif --}}

@if (session('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <p>{{ session('message') }}</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- uploadActivityModal -->
<div class="modal fade" id="uploadActivityModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadActivityModalTitle">Upload Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="">Activity Description</label>
                    <textarea class="form-control" id="activityDescription" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="document">Activities</label>
                    <div class="needsclick dropzone" id="document-dropzone">

                    </div>
                </div>
                <div class="form-group">
                    <label for="document">Uploaded File</label>
                    <div class="" id="uploaded-image">

                    </div>
                </div>
                <input type="hidden" id="bookingActivityId" name="bookingActivityId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="uploadActivityModalBtn" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>



<!-- add meeting Modal -->
<div class="modal fade" id="addMeetingLinkModal" tabindex="-1" role="dialog" aria-labelledby="addMeetingLinkModalTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMeetingLinkModalTitle">Meeting Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="meetingLink" placeholder="Google Meet">
                    </div>
                    <div class="col-md-4 d-flex justify-content-around">
                        <button type="button" class="btn btn-secondary" id="clipboard" onclick="copyText()"><i
                                class="fas fa-clipboard"></i></button>
                        <button type="button" class="btn btn-secondary" id="openLink" onclick="openLink()"><i
                                class="fas fa-external-link-alt"></i></i></button>

                    </div>
                </div>
                <input type="hidden" id="bookingId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="addMeetingLinkModalBtn" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>





<div class="row">
    <div class="col-sm-12">
        <table id="bookingTable" class="table table-bordered table-hover dataTable dtr-inline">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>title</th>
                    <th>subject</th>
                    <th>Start Date</th>
                    <th>Time</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

        </table>
    </div>
</div>




@endsection


@section('additional_script')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"> </script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset("assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatables-responsive/js/dataTables.responsive.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatables-buttons/js/dataTables.buttons.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js") }}"></script>
<script src="{{ asset("assets/plugins/jszip/jszip.min.js") }}"></script>
<script src="{{ asset("assets/plugins/pdfmake/pdfmake.min.js") }}"></script>
<script src="{{ asset("assets/plugins/pdfmake/vfs_fonts.js") }}"></script>
<script src="{{ asset("assets/plugins/datatables-buttons/js/buttons.html5.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatables-buttons/js/buttons.print.min.js") }}"></script>
<script src="{{ asset("assets/plugins/datatables-buttons/js/buttons.colVis.min.js") }}"></script>
<!-- dropzonejs -->
<script src="{{ asset("assets/plugins/dropzone/min/dropzone.min.js") }}"></script>
<script>
    $(document).ready( function () {
        $('#bookingTable').DataTable({
            // "dom": '<"toolbar">frtip',
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('booked-session.index') }}",
            "columns": [
                { "data": "id"},
                { "data": "user.name" },
                { "data": "user.phone_number" },
                { "data": "tutoring.title" },
                { "data": "tutoring.subject.name" },
                { "data": "tutoring.start_time",
                render: function(data, type, row){
                        if(type === "sort" || type === "type"){
                            return data;
                        }
                        return moment(data).format('dddd, MMMM Do YYYY');
                    }
                },
                { "data": null,
                render: function(data, type, row){
                        if(type === "sort" || type === "type"){
                            return data;
                        }
                        return moment(data.tutoring.start_time).format('h:mm a') + " - " + moment(data.tutoring.end_time).format('h:mm a');
                    },
                 orderable: false, searchable: false
                },
                { "data": "payment", name:"payment",orderable: false, searchable: false},
                { "data": "status"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
        });
    });
</script>

<script>
    $('#addMeetingLinkModal').on('show.bs.modal', function(e) {
    let link     = e.relatedTarget,
        modal    = $(this);
        id = link.dataset.id,
        meetingLink = link.dataset.meetingLink;
    modal.find("#bookingId").val(id);
    if(meetingLink) {
        modal.find("#meetingLink").val(meetingLink);
    }
});

$('#addMeetingLinkModal').on('hidden.bs.modal', function(e) {
   $('#addMeetingLinkModal').find("#meetingLink").val("");
});


$('#addMeetingLinkModalBtn').click(function (){
   let modal = $('#addMeetingLinkModal');
   let meetingLink = modal.find("#meetingLink").val();
   let id = modal.find("#bookingId").val();

   let csrf = "{{ csrf_token() }}"
   let formData = {_method:"POST", _token:csrf, meeting_link:meetingLink}; //Array
    $.ajax({
        url : `/home/tutor/booked-sessions/${id}/meeting-link`, // Url of backend (can be python, php, etc..)
        type: "POST", // data type (can be get, post, put, delete)
        data : formData, // data in json format
        async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
        success: function(response, textStatus, jqXHR) {
            window.location.replace(response);
        },
        error: function (xhr) {
            $('#addMeetingLinkModal').modal('hide');
            $('#validationErrorsAlert').addClass('show');
            $.each(xhr.responseJSON.errors, function(key,value) {
                $('#validationErrors').append('<li>'+value+'</li>');
            });

        }
    });
   });

function isValidHttpUrl(string) {
  let url;

  try {
    url = new URL(string);
  } catch (_) {
    return false;
  }

  return url.protocol === "http:" || url.protocol === "https:";
}

// $('#meetingLink').on('keyup',function (){
//     function timer(){
//         var link = $('#meetingLink').val();
//         let isUrlValid = isValidHttpUrl(link);
//         if(isUrlValid) {
//             $('#clipboard').attr("disabled",false);
//             $('#openLink').attr("disabled",false);
//         }
//     }

//     setTimeout(timer,100);
// })
</script>

<script>
    function copyText() {
  /* Get the text field */
  var copyText = document.getElementById("meetingLink");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  alert("Copied the link: " + copyText.value);
}

function openLink() {
  /* Get the text field */
  var text = document.getElementById("meetingLink").value;

  window.open(
              text, "_blank");

}
</script>
<script>
    $('#uploadActivityModal').on('show.bs.modal', function(e) {
    let link     = e.relatedTarget,
        modal    = $(this),
        id = link.dataset.id,
        activityDescription = link.dataset.activityDescription;
        console.log(link.dataset);

    console.log(activityDescription);
    modal.find("#bookingActivityId").val(id);
    loadImages();
    if(meetingLink) {
        modal.find("#activityDescription").val(activityDescription);
    }

});
    Dropzone.options.documentDropzone = {
      method: 'POST',
      url: '{{ route('dropzone.upload') }}',
      maxFilesize: 5, // MB
      maxFiles: 5,
      autoProcessQueue : true,
      headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      paramName:"images",
      uploadMultiple:true,
      acceptedFiles: ".jpeg,.jpg,.png",
      init: function() {
          var submitButton = document.querySelector("#uploadActivityModalBtn");
          myDropzone = this;
          submitButton.addEventListener('click', function() {
              myDropzone.processQueue();
          });

          this.on("complete", function() {
              if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                  var _this = this
                  _this.removeAllFiles();
              }
              loadImages();

          });

          this.on("sending", function(file, xhr, formData){
                let modal = $('#uploadActivityModal');
                let id = modal.find("#bookingActivityId").val();
                console.log(id);
                formData.append('bookingId', id);
            },)


      },

      error: function(file, message) {
        console.log(message);
      },
      success: function (file, response) {
        // $(file.previewTemplate).append('<span class="server_file">'+response.paths+'</span>');
        console.log(file);
    },
    }

    function loadImages() {
        let modal = $('#uploadActivityModal');
        let id = modal.find("#bookingActivityId").val();
        $.ajax({
            url: '{{ route("dropzone.fetch") }}',
            data: {'bookingId': id},
            success: function(response) {
                var content ="<p class='text-center'>You don't have activity's pictures uploaded</p>";
                if(response.data) {
                    content = response.data;
                }
                $('#uploaded-image').html(content);
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }

    $(document).on('click','.remove-image', function(){
        let modal = $('#uploadActivityModal');
        let bookingId = modal.find("#bookingActivityId").val();
        var imageId = $(this).attr('id');
        var text = $(this).val();
        $(this).html('<div class="spinner-border text-danger"></div>')
        $.ajax({
            url:"{{ route('dropzone.delete') }}",
            data: {bookingId:bookingId,imageId:imageId},
            success: function(data) {
                loadImages();

            }
        });
    });
</script>
<script>
$('#uploadActivityModalBtn').click(function (){
   let modal = $('#uploadActivityModal');
   let activityDescription = modal.find("#activityDescription").val();
   let id = modal.find("#bookingActivityId").val();

   console.log(activityDescription);

   let csrf = "{{ csrf_token() }}"
   let formData = {_method:"POST", _token:csrf, activity_description:activityDescription}; //Array
    $.ajax({
        url : `/home/tutor/booked-sessions/${id}?action=upload_activity`, // Url of backend (can be python, php, etc..)
        type: "POST", // data type (can be get, post, put, delete)
        data : formData, // data in json format
        async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
        success: function(response, textStatus, jqXHR) {
            console.log(response);
            window.location.replace(response);
        },
        error: function (xhr) {
            $('#uploadActivityModal').modal('hide');
            $('#validationErrorsAlert').addClass('show');
            $.each(xhr.responseJSON.errors, function(key,value) {
                $('#validationErrors').append('<li>'+value+'</li>');
            });

        }
    });
   });
</script>
@endsection
