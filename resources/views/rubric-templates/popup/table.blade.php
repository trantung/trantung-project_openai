<div class="alert alert-success hide" role="alert">
   Cập nhật thành công
</div>
<table class="table-popup">
   <tbody class="center ui-sortable">
      <input type="hidden" id="api_ems_ids" value="">
      <input type="hidden" id="rubric_template_popup_id" value="{{$rubricTemplateId}}">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      @foreach ($emsTypes as $key=>$emsType)
      <tr data-toggle="collapse" style="cursor: pointer" data-target="#collapseEmsType{{$key}}" aria-expanded="true" aria-controls="collapseExample">
         <td class="left bi_name">
            <div>{{$emsType->type_name}}</div>
         </td>
      </tr>
      <tr class="collapse show" id="collapseEmsType{{$key}}">
         <td colspan="3">
            <div class="card card-body">
               @foreach ($emsType->api_ems as $apiEms)
               <div>
                  <input type="checkbox" class="api_ems_id" value="{{$apiEms->id}}" {{$apiEms->rubric_template_id ? 'checked' : ''}}>
                  <label for="">{{$apiEms->ems_name}}</label>
               </div>
               @endforeach
            </div>
         </td>
      </tr>
      @endforeach
      @if ($emsTypes->isEmpty())
          <tr>
            <td>Không có dữ liệu</td>
          </tr>
      @endif
   </tbody>
</table>