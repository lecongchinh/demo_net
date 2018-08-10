

       
	   <table class="table table-bordered" id="table_datatable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Username</th>
                <th>Email</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
    </table>
</div>

<div class="modal" id="myModalEdit">
    <div class="modal-dialog">
    <div class="modal-content">
    
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Edit User</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            
            <div class="container">
                <form method="post" action="javascript:void(0);" id="get_id">
                    <div class="form-group">
                        <label>Username:</label>
                        <input class="form-control username" name="username">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" class="form-control email" name="email">
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" class="form-control password" placeholder="Enter password" name="password">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password:</label>
                        <input type="password" class="form-control password" placeholder="Confirm password" name="password_confirm">
                    </div>
                    </div>
                    <div class="text-center">
                        <button onclick="post_edit()" class="btn btn-primary">Submit</button>
                    </div>  
                    <input type="hidden" name="id_user">
                </form>
            </div>

        </div>       
    </div>
    </div>
</div>

<div class="modal" id="myModalCreate">
    <div class="modal-dialog">
    <div class="modal-content">
    
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Create User</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            
            <div class="container">
                <form method="post" action="javascript:void(0);" id="modal_create">
                    <div class="form-group test">
                        <label>Username:</label>
                        <input class="form-control" placeholder="Moi ban nhap" name="username">
                        <div class="alert alert-danger username" id="username_error"></div>
                    </div>
                    <div class="form-group test">
                        <label>Email:</label>
                        <input type="email" placeholder="Moi ban nhap email" class="form-control" name="email">
                        <div class="alert alert-danger email" id="email_error"></div>
                    </div>
                    <div class="form-group test">
                        <label>Password:</label>
                        <input type="password" class="form-control" placeholder="Moi ban nhap password" name="password">
                        <div class="alert alert-danger password" id="password_error"></div>
                    </div>
                    </div>
                    <div class="text-center">
                        <button onclick="post_create()" class="btn btn-primary">Submit</button>
                    </div>  
                </form>
            </div>

        </div>
    </div>
    </div>
</div>

<script>
	var dataTable = $('#table_datatable').DataTable({  
		"processing":true,  
		"serverSide":true,  
		"order":[],  
		"ajax":{  
				url:"fetch_user",  
				type:"POST"  
		},  
		"columnDefs":[  
				{  
					"targets":[-1],  
					"orderable":false,  
				},  
		],  
	});  

	function get_create() {
        $('.test .username').hide();
        $('.test .email').hide();
        $('.test .password').hide();
        $('#myModalCreate').modal('show');
        $('#modal_create').trigger("reset");
    }

    function post_create() {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'create',
            data: 
                $('#modal_create').serialize()
            ,
            success: function(data) {
                console.log(data);
                if(data.status == true) {
                    $('#myModalCreate').modal('hide');
                    setInterval();
                }  else{
                    $('.test .username').hide();
                    $('.test .email').hide();
                    $('.test .password').hide();
                    for(var key in data.mess) {
                        $('.test .'+ key +' ').show();
                        $('.test .'+ key +' ').html(data.mess[key]);                     
                    }
                }

            }
        })
    }

	function delete_user(id) {
        $.ajax({
            type:'post',
            dataType:'json',
            data:{
                id:id
            },
            url: 'delete/' + id, 
            success: function(data){
                setInterval();
            }
        });
    }
	
	function edit_user($id) {
        $('#myModalEdit').modal('show');
        $('#get_id').trigger("reset");
        
        $.ajax({
            type: 'get', 
            dataType: 'json',
            url: 'edit/' + $id,
           
            success: function(results) {
                $('.username').val(results[0].username);
                $('.email').val(results[0].email);
                // $('.password').val('');
				$('[name="id_user"]').val(results[0].id);
            }
        })
    }

    function post_edit() {
        $.ajax({
            type: 'post',
            dataType: 'json', 
            url: 'edit/' + $('[name="id_user"]').val() ,
            data: 
                $('#get_id').serialize(),

            success: function(data) {
                if(data.status == true) {
                    $('#myModalEdit').modal('hide');
                    setInterval();
                } 

            }
        })
    }
	
	
	function setInterval(){
		dataTable.ajax.reload();
	}(3000);

</script>


