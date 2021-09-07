 @php
 $dataArray['items'] = $dataArray['items']->sortByDesc('id');
 $settings= $dataArray['settings'];
 $fieldList=$settings->setting[0]['fieldList'];
 $routes=$settings->setting[0]['routes'];
 $componentDetails=$settings->setting[0]['componentDetails'];
 $items= $dataArray['items'];
 $page_name = $dataArray['page_name'];
 $GLOBALS['CurrentUser']= auth()->user();   

 @endphp
 <script>
     var dataArray = @json($dataArray);
     var dataArrayLength = "{{ sizeof($items) }}"
     console.log(dataArray)

 </script>


 @if ($errors->any())
 <div class="alert alert-danger">
     <ul>
         @foreach ($errors->all() as $error)
         <li>{{ __('translate.'.$error) }}</li>
         @endforeach
     </ul>
 </div>
 @endif

 @if (session()->has('success'))
 <div class="alert alert-success">
     @if(is_array(session('success')))
     <ul>
         @foreach (session('success') as $message)
         <li>{{  __('translate.'.$message) }}</li>
         @endforeach
     </ul>
     @else
     {{ session('success') }}
     @endif
 </div>
 @endif



 <!-- Begin Page Content -->
 <div class="collapse" id="createNewForm">
     <div class="card mb-4 shadow">

         <div class="card-header py-3  bg-abasas-dark ">
             <nav class="navbar navbar-dark">
                 <a class="navbar-brand text-light"> {{ __('translate.Add new')  }}</a>
             </nav>
         </div>
         <div class="card-body">
             <form method="POST" action="{{ route($routes['create']['name']) }}">
                 @csrf
                 <div class="form-row align-items-center" id="createFormFieldList">












                 </div>
                 <div class="col-12">
                     <button type="submit" class="btn bg-abasas-dark mt-3">{{ __('translate.Submit')  }}</button>
                 </div>

             </form>
         </div>
     </div>
 </div>






 <div class="card shadow mb-4">

     <div class="card-header py-3 bg-abasas-dark">
         <nav class="navbar  ">

             <div class="navbar-brand"><span id="componentDetailsTitle">
                     {{ __('translate.'.$componentDetails['title'])  }}</span>   @can('Super Admin') <i class="fas fa-tools pl-2"
                     id="pageSetting"></i> @endcan </div>
            @can($page_name.' Read')
            <div id="searchByMonth"></div>
            @endcan
             @can($page_name.' Create')
             <div id="AddNewFormButtonDiv"><button type="button" class="btn btn-success btn-lg" id="AddNewFormButton"
                     data-toggle="collapse" data-target="#createNewForm" aria-expanded="false"
                     aria-controls="collapseExample"><i class="fas fa-plus" id="PlusButton"></i></button></div>

             @endcan
             
         </nav>
     </div>

     @can($page_name.' Read')
     <div class="card-body">

         <div class="table-responsive">
             <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                 <thead class="bg-abasas-dark">

                     <tr>

                         <th> #</th>
                         @foreach( $fieldList as $field)


                         @if($field['read']==1)

                         <th> {{ __('translate.'.$field['title'])  }}</th>

                         @endif
                         @endforeach

                         @if( $GLOBALS['CurrentUser']->can($page_name.' Delete') || $GLOBALS['CurrentUser']->can($page_name.' Edit') ||
                         $GLOBALS['CurrentUser']->can($page_name.' View') )
                         <th>{{__('translate.Action')}}</th>
                         @endif

                     </tr>
                 </thead>
                 <tfoot class="bg-abasas-dark">
                     <tr>

                         <th> #</th>
                         @foreach( $fieldList as $field)


                         @if($field['read']==1)

                         <th> {{ __('translate.'.$field['title'])  }}</th>

                         @endif
                         @endforeach

                         @if( $GLOBALS['CurrentUser']->can($page_name.' Delete') || $GLOBALS['CurrentUser']->can($page_name.' Edit') ||
                         $GLOBALS['CurrentUser']->can($page_name.' View') )
                         <th>{{__('translate.Action')}}</th>
                         @endif

                     </tr>

                 </tfoot>

                 <tbody>

                     <?php $itr = 1; ?>
                     @foreach ($items as $item)

                     @php
                     $item->abasas();
                     $itemId = $item->id;
                     @endphp

                     <tr class="data-row">
                         <td class="iteration">{{$itr++}}</td>

                         @foreach( $fieldList as $field)

                         @if($field['read']==1)
                         @php
                         $name= $field['name'];
                         @endphp



                         <td class="  word-break  {{$field['database_name']}} "> {{ $item->$name}}</td>


                         @endif

                         @endforeach







                         @if( $GLOBALS['CurrentUser']->can($page_name.' Delete') || $GLOBALS['CurrentUser']->can($page_name.' Edit') ||
                         $GLOBALS['CurrentUser']->can($page_name.' View') )
                         <td class="align-middle">
                             @can($page_name.' Edit')
                             <button title="Edit" type="button" class="dataEditItemClass btn btn-success btn-sm"
                                 id="data-edit-button" data-item-id={{$itemId}} data-item-index={{$itr-2}}> <i
                                     class="fa fa-edit" aria-hidden="false"> </i></button>
                             @endcan
                             @can($page_name.' Delete')
                             <form method="POST" action="{{route($routes['delete']['name'],$itemId)}}"
                                 id="delete-form-{{ $item->id }}" style="display:none; ">
                                 {{csrf_field() }}
                                 {{ method_field("delete") }}
                             </form>




                             <button title="Delete" class="dataDeleteItemClass btn btn-danger btn-sm" onclick="if(confirm('are you sure to delete this')){
				document.getElementById('delete-form-{{ $item->id }}').submit();
			}
			else{
				event.preventDefault();
			}
			" class="btn btn-danger btn-sm btn-raised">
                                 <i class="fa fa-trash" aria-hidden="false">

                                 </i>
                             </button>
                             @endcan

                             {{-- @php
                             $allowedTables = array("categories", "brands", "employees", "customers", "suppliers");
                             @endphp
                             @if (in_array($routes['create']['link'], $allowedTables)) --}}
                             @can($page_name.' View')
                             <a href="{{ route($routes['create']['link'].'.show',$itemId) }}"> <button title="View"
                                     type="button" class="dataEditItemClass btn btn-info btn-sm" id="data-show-button">
                                     <i class="fa fa-eye text-white" aria-hidden="false"> </i></button></a>

                             @endcan



                         </td>
                         @endif

                     </tr>
                     @endforeach

                 </tbody>


             </table>
         </div>

     </div>
     @endcan
 </div>









 <!-- Attachment Modal -->
 <div class="modal fade" id="data-edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
     aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header bg-abasas-dark">
                 <h5 class="modal-title " id="edit-modal-label ">
                     {{ __('translate.'.$componentDetails['editTitle'])}} </h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                         aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body" id="attachment-body-content">
                 <form id="data-edit-form" class="form-horizontal" method="POST" action="">
                     @csrf
                     @method('put')
                     <div class="form-group">
                         <label class="col-form-label" for="modal-update-hidden-id">{{__('translate.Id')}} </label>
                         <input type="text" name="id" class="form-control" id="modal-update-hidden-id" required
                             readonly>
                     </div>

                     <div id="editOptions"></div>





                     <div class="form-group">

                         <input type="submit" id="submit-button" value=" {{__('translate.Submit')}}"
                             class="form-control btn btn-success">
                     </div>




                 </form>
             </div>

         </div>
     </div>
 </div>
 <!-- /Attachment Modal -->


 @can('Super Admin')
 <!-- Attachment Modal -->
 <div class="modal fade" id="setting-modal" tabindex="-1" role="dialog" aria-labelledby="setting-modal-label"
     aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header ">


                 <ul class="nav nav-tabs" id="myTab" role="tablist">
                     <li class="nav-item">
                         <a class="nav-link active " id="setting-tab" data-toggle="tab" href="#Setting" role="tab"
                             aria-controls="Setting" aria-selected="true"><b>{{__('translate.Setting')}}</b> </a>
                     </li>
                   


                     <li class="nav-item">
                         <a class="nav-link" id="permission-tab" data-toggle="tab" href="#permission" role="tab"
                             aria-controls="permission" aria-selected="false"><b> {{__('translate.Permission')}}</b></a>
                     </li>
                    
                 </ul>
                 {{-- <h5 class="modal-title " id="setting-modal-label "> {{ __('translate.'.$componentDetails['title'])  }}
                 </h5> --}}



                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                         aria-hidden="true">&times;</span>
                 </button>
             </div>


             <div class="tab-content" id="myTabContent">
                 <div class="tab-pane fade show active" id="Setting" role="tabpanel" aria-labelledby="setting-tab">


                     <div class="modal-body" id="attachment-body-content">




                         <table class="table table-striped">

                             <tbody id="sortable">
                                 @for( $i=0 ; $i<count($fieldList) ; $i++) <tr
                                     data-position="{{ $fieldList[$i]['position'] }}"
                                     data-name="{{ $fieldList[$i]['name'] }}">
                                     <th scope="row"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                         {{ __('translate.'. $fieldList[$i]['title'] )  }}</th>
                                     <td>
                                         <div class="form-check-inline">
                                            
                                             <label class="form-check-label createLabel" @if( !$GLOBALS['CurrentUser']->can($page_name.' Create')) hidden @endif>
                                                 @if( $fieldList[$i]['create']==1 )
                                                 <input type="checkbox" class="form-check-input create abasasCheckBox "
                                                     value="1" checked>
                                                 @elseif( $fieldList[$i]['create']==2 )
                                                 <input type="checkbox" class="form-check-input create abasasCheckBox "
                                                     value="2" checked disabled>

                                                 @elseif($fieldList[$i]['create']==0)

                                                 <input type="checkbox"
                                                     class="form-check-input  create abasasCheckBox  " value="0">
                                                 @else

                                                 <input type="checkbox" class="form-check-input create" disabled
                                                     value="3">
                                                 @endif
                                                 {{ __('translate.Create')  }} </label>
                                                 
                                         </div>
                                         <div class="form-check-inline">
                                             <label class="form-check-label readLabel" @if( !$GLOBALS['CurrentUser']->can($page_name.' Read')) hidden @endif>
                                                 @if( $fieldList[$i]['read'] == 1 )
                                                 <input type="checkbox" class="form-check-input read abasasCheckBox "
                                                     value="1" checked>
                                                 @elseif( $fieldList[$i]['read'] == 0 )

                                                 <input type="checkbox" class="form-check-input read abasasCheckBox "
                                                     value="0">
                                                 @elseif( $fieldList[$i]['read'] == 2 )

                                                 <input type="checkbox" class="form-check-input read abasasCheckBox "
                                                     value="2" checked disabled>
                                                 @else

                                                 <input type="checkbox" class="form-check-input read" disabled
                                                     value="3">
                                                 @endif
                                                 {{ __('translate.Read')  }}
                                             </label>
                                             
                                         </div>
                                         <div class="form-check-inline">
                                             <label class="form-check-label updateLabel" @if( !$GLOBALS['CurrentUser']->can($page_name.' Edit')) hidden @endif>
                                                 @if( $fieldList[$i]['update'] ==1 )
                                                 <input type="checkbox" class="form-check-input update abasasCheckBox "
                                                     value="1" checked>
                                                 @elseif( $fieldList[$i]['update'] ==2 )
                                                 <input type="checkbox" class="form-check-input update abasasCheckBox "
                                                     value="2" checked disabled>
                                                 @elseif( $fieldList[$i]['update'] ==0 )
                                                 <input type="checkbox" class="form-check-input update abasasCheckBox "
                                                     value="0">
                                                 @else
                                                 <input type="checkbox" class="form-check-input update" disabled
                                                     value="3">
                                                 @endif
                                                 {{ __('translate.Update')  }}
                                             </label>
                                             
                                         </div>


                                     </td>

                                     </tr>


                                     @endfor



                             </tbody>
                         </table>


                     </div>

                     <div class="modal-footer">
                         <div class="btn bg-abasas-dark btn-block " id="settingsSaveButton"> {{ __('translate.Save')  }}
                         </div>
                     </div>


                 </div>


                 <div class="tab-pane fade" id="permission" role="tabpanel" aria-labelledby="permission-tab">
                     <form action="{{ route('rolepermissionstore') }}" method="post">
                         @csrf
                         <input type="text" name="page_name" value="{{ $page_name }}" hidden>
                         <div class="modal-body">


                             <div class="table-responsive">
                                 <table class="table table-striped table-bordered"  width="100%"
                                     cellspacing="0">
                                     <thead class="bg-abasas-dark">

                                         <tr>

                                             <th>{{ __('translate.Permission') }} </th>

                                             @for ($i=1 ; $i<5 ; $i++) <th>{{ $roles[$i]->name }}</th>
                                                 @endfor
                                         </tr>
                                     </thead>


                                     <tbody>

                                         @php
                                         $itr = 1;
                                         @endphp



                                         @php
                                         $permision_name = $page_name." Page";
                                         $permission = $permissions->where('name',$permision_name)->first();
                                         @endphp
                                         @if (!is_null($permission))

                                         <tr class="data-row">
                                             <td class="iteration">{{ __('translate.Page Access') }}</td>
                                             @for ($i=1 ; $i<5 ; $i++) <td
                                                 class="word-break name justify-content-center">
                                                 <label class="checkbox-inline"><input type="checkbox"
                                                         name="page{{ $i }}"
                                                         @if($roles[$i]->hasPermissionTo($permision_name)) checked
                                                     @endif></label>
                                                 </td>
                                                 @endfor

                                         </tr>

                                         @endif

                                         @php
                                         $permision_name = $page_name." Create";
                                         $permission = $permissions->where('name',$permision_name)->first();

                                         @endphp
                                         @if (!is_null($permission))

                                         <tr class="data-row">
                                             <td class="iteration">{{ __('translate.Create Access') }} </td>

                                             @for ($i=1 ; $i<5 ; $i++) <td
                                                 class="word-break name justify-content-center">
                                                 <label class="checkbox-inline"><input type="checkbox"
                                                         name="create{{ $i }}"
                                                         @if($roles[$i]->hasPermissionTo($permision_name)) checked
                                                     @endif></label>
                                                 </td>
                                                 @endfor

                                         </tr>
                                         @endif
                                         @php
                                         $permision_name = $page_name." Read";
                                         $permission = $permissions->where('name',$permision_name)->first();

                                         @endphp
                                         @if (!is_null($permission))

                                         <tr class="data-row">
                                             <td class="iteration">{{ __('translate.Read Access') }} </td>


                                             @for ($i=1 ; $i<5 ; $i++) <td class="word-break name justify-content-center">
                                                 <label class="checkbox-inline"><input type="checkbox"
                                                         name="read{{ $i }}"
                                                         @if($roles[$i]->hasPermissionTo($permision_name)) checked
                                                     @endif></label>
                                                 </td>
                                                 @endfor

                                         </tr>
                                         @endif
                                         @php
                                         $permision_name = $page_name." Edit";
                                         $permission = $permissions->where('name',$permision_name)->first();

                                         @endphp
                                         @if (!is_null($permission))

                                         <tr class="data-row">
                                             <td class="iteration">{{ __('translate.Edit Access') }} </td>


                                             @for ($i=1 ; $i<5 ; $i++) <td
                                                 class="word-break name justify-content-center">
                                                 <label class="checkbox-inline"><input type="checkbox"
                                                         name="edit{{ $i }}"
                                                         @if($roles[$i]->hasPermissionTo($permision_name)) checked
                                                     @endif></label>
                                                 </td>
                                                 @endfor

                                         </tr>
                                         @endif
                                         @php
                                         $permision_name = $page_name." Delete";
                                         $permission = $permissions->where('name',$permision_name)->first();

                                         @endphp
                                         @if (!is_null($permission))

                                         <tr class="data-row">
                                             <td class="iteration">{{ __('translate.Delete Access') }} </td>


                                             @for ($i=1 ; $i<5 ; $i++) <td
                                                 class="word-break name justify-content-center">
                                                 <label class="checkbox-inline"><input type="checkbox"
                                                         name="delete{{ $i }}"
                                                         @if($roles[$i]->hasPermissionTo($permision_name)) checked
                                                     @endif></label>
                                                 </td>
                                                 @endfor

                                         </tr>
                                         @endif
                                         @php
                                         $permision_name = $page_name." View";
                                         $permission = $permissions->where('name',$permision_name)->first();
                                         @endphp
                                         @if (!is_null($permission))

                                         <tr class="data-row">
                                             <td class="iteration">{{ __('translate.view Access') }}</td>
                                             @for ($i=1 ; $i<5 ; $i++) <td
                                                 class="word-break name justify-content-center">
                                                 <label class="checkbox-inline"><input type="checkbox"
                                                         name="view{{ $i }}"
                                                         @if($roles[$i]->hasPermissionTo($permision_name)) checked
                                                     @endif></label>
                                                 </td>
                                                 @endfor

                                         </tr>

                                         @endif

                                         @php
                                         $permision_name = $page_name." Graph";
                                         $permission = $permissions->where('name',$permision_name)->first();
                                         @endphp
                                         @if (!is_null($permission))

                                         <tr class="data-row">
                                             <td class="iteration">{{ __('translate.Graph Access') }}</td>
                                             @for ($i=1 ; $i<5 ; $i++) <td
                                                 class="word-break name justify-content-center">
                                                 <label class="checkbox-inline"><input type="checkbox"
                                                         name="graph{{ $i }}"
                                                         @if($roles[$i]->hasPermissionTo($permision_name)) checked
                                                     @endif></label>
                                                 </td>
                                                 @endfor

                                         </tr>

                                         @endif




                                     </tbody>



                                 </table>
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="submit"
                                 class="btn bg-abasas-dark btn-block form-control  ">{{ __('translate.Save')  }}</button>
                         </div>
                     </form>
                 </div>
             </div>




         </div>
     </div>
 </div>
 <!-- /Attachment Modal -->

 @endcan

 <script>
     /**
      * for showing edit item popup
      */

     $(document).ready(function () {



         $('body').on('click', '#AddNewFormButton', function () {
             $('#PlusButton').toggleClass('fa-plus').toggleClass('fa-minus');

         });




         $(document).on('click', "#data-edit-button", function () {


             $(this).addClass(
                 'edit-item-trigger-clicked'
             ); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

             var options = {
                 'backdrop': 'static'
             };
             $('#data-edit-modal').modal(options)
         });

         // on modal show
         $('#data-edit-modal').on('show.bs.modal', function () {


             var el = $(".edit-item-trigger-clicked"); // See how its usefull right here? 
             var row = el.closest(".data-row");

             // get the data
             var itemId = el.data('item-id');
             var itemIndex = dataArrayLength - 1 - el.data('item-index');
             dataArrayLength
             $("#modal-update-hidden-id").val(itemId);


             var home = "{{route('home')}}";
             var link = "{{$routes['update']['link']}}"
             var action = home.trim() + '/' + link.trim() + '/' + itemId;

             $("#data-edit-form").attr('action', action);
             $("#editOptions").html('');

             var itemArray = @json($items);
             var selectedItem = itemArray[itemIndex];
             //                  console.log(selectedItem.name)

             @foreach($fieldList as $field)

             @php
             $require = $field['require']
             @endphp

             @if($field['update'] == 1 || $field['update'] == 2)

             @if($field['input_type'] == 'dropDown')




             var databaseName = "{{$field['database_name']}}";
             var dropDownDataArray = dataArray["{{$field['data']}}"];
             console.log(dropDownDataArray);

             var dropDownId = selectedItem["{{$field['database_name']}}"];

             console.log(@json($field['data']));

             html = "";

             html += '<div class="form-group">';
             html += '<label class="col-form-label" >  {{ __("translate.".$field["title"]  )}} ' + @if(
                 $require == 1)
             '<span style="color: red"> *</span> '
             @else ""
             @endif + '  </label>';
             html += '<select class="form-control form-control" name="' + databaseName +
                 '"  required >';


             $.each(dropDownDataArray, function (key) {
                 if (dropDownDataArray[key].id == dropDownId) {
                     html += '<option value="' + dropDownDataArray[key].id +
                         ' "  selected="selected"   >' + dropDownDataArray[key].name +
                         '</option>';
                 } else {
                     html += '<option value="' + dropDownDataArray[key].id + '" >' +
                         dropDownDataArray[
                             key].name + '</option>';
                 }

             });



             html += '</select>';
             html += '</div>';

             $("#editOptions").append(html);




             @else

             var database_name = "{{$field['database_name']}}";
             var value = selectedItem[database_name];
             var inputType = "{{ $field['input_type'] }}";

             html = "";
             html += '<div class="form-group">';
             html += '<label class="col-form-label" >   {{ __("translate.".$field["title"] ) }} ' + @if(
                 $require == 1)
             '<span style="color: red"> *</span>'
             @else ""
             @endif + '   </label>';

             html += '<input type="' + inputType + '" ' + @if($field["input_type"] == "number")
             ' step="any" '
             @else ""
             @endif + ' name="' + database_name + '" value="' + value +
                 '" class="form-control" ' + @if($require == 1)
             'required'
             @endif + '>';
             html += '</div>';

             $("#editOptions").append(html);



             @endif
             @endif

             @endforeach



         });

         // on modal hide
         $('#data-edit-modal').on('hide.bs.modal', function () {
             $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
             $("#edit-form").trigger("reset");
         });



         $('#dataTable').DataTable({
             dom: 'lBfrtip',
             buttons: [
                 'csv', 'excel', 'pdf', 'print'
             ]
         });



         $(document).on('click', "#pageSetting", function () {
             var options = {
                 'backdrop': 'static'
             };
             $('#setting-modal').modal(options)
         })


         $("#sortable").sortable({

             update: function (event, ui) {
                 $(this).children().each(function (index) {

                     if ($(this).attr('data-position') != index + 1) {
                         $(this).attr('data-position', index + 1)
                     }
                 });

             }
         });

     });



     $("#settingsSaveButton").on('click', function () {
         var positionArray = {
             "_token": $("#csrfToken").val().trim()

         };

         $("#sortable").children().each(function (index) {
             var name = $(this).attr('data-name').trim()
             var position = $(this).attr('data-position').trim();
             var create = $(this).find('.create').val().trim();
             var read = $(this).find('.read').val().trim();
             var update = $(this).find('.update').val().trim();



             positionArray[name] = {
                 position: position,
                 create: create,
                 read: read,
                 update: update

             };

             console.log(positionArray);
         });
         saveSettings(positionArray);

     });

     function saveSettings(positionArray) {
         var url = $("#homeRoute").val().trim() + "/settings/" + "{{ $settings->id }}";
         // console.log(url);
         $.ajax({
             url: url,
             data: positionArray,
             type: 'put',
             success: function (data) {
                 location.reload(true);
                 console.log(data);
             },
             error: function (data) {
                 console.log(data);
             }
         });
     }





     insertInputFormData();

     function insertInputFormData() {


         var home = "{{route('home')}}";
         var link = "{{$routes['create']['link']}}"
         var action = home.trim() + '/' + link.trim();

         $("#createNewForm").attr('action', action);
         $("#createFormFieldList").html('');

         var itemArray = @json($items);
         //                  console.log(selectedItem.name)

         @foreach($fieldList as $field)

         @php
         $require = $field['require']
         @endphp

         @if($field['create'] == 1 || $field['create'] == 2)

         @if($field['input_type'] == 'dropDown')




         var databaseName = "{{$field['database_name']}}";
         var dropDownDataArray = dataArray["{{$field['data']}}"];



         html = "";

         html += '<div class="form-group col-md-4 col-sm-12 p-0 p-md-4">';
         html += '<label class="col-form-label" >  {{ __("translate.".$field["title"] ) }} ' + @if($require == 1)
         '<span style="color: red"> *</span>'
         @else ""
         @endif + '  </label>';
         html += '<select class="form-control form-control" name="' + databaseName +
             '"  required > <option disabled selected value=""> -- select an option -- </option>';


         $.each(dropDownDataArray, function (key) {

             html += '<option value="' + dropDownDataArray[key].id + '" >' + dropDownDataArray[
                 key].name + '</option>';


         });



         html += '</select>';
         html += '</div>';

         $("#createFormFieldList").append(html);




         @else

         var database_name = "{{$field['database_name']}}";

         var inputType = "{{ $field['input_type'] }}";

         //                     <div class="col-auto">

         // // <span class="text-dark pl-4">নাম্বার</span>
         // // <input type="number" name="phone" class="form-control mb-2"  >
         // // </div>

         html = "";
         html += '<div class="col-md-4 col-sm-12   p-0 p-md-4">';
         html += '<label class="text-dark "> {{ __("translate.".$field["title"])  }} ' + @if($require == 1)
         '<span style="color: red"> *</span>'
         @else ""
         @endif + '   </label>';

         html += '<input type="' + inputType + '"  ' + @if($field["input_type"] == "number")
         ' step="any" '
         @else ""
         @endif + '  name="' + database_name + '" class="form-control" ' + @if($require == 1)
         'required'
         @endif + '>';
         html += '</div>';

         $("#createFormFieldList").append(html);



         @endif
         @endif

         @endforeach


     }









     // $(document).one('click dblclick keypress',function(){
     //     alert("hahah");
     // })

 </script>

 {{ $slot }}
