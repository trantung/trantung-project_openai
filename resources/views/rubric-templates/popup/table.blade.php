<input type="hidden" id="rubric_template_popup_id" value="{{$rubricTemplateId}}">
<div class="table-popup-rubric-template">
    <table class="table-popup table-bordered">
        <thead>
            <th style="text-align: center">Ems</th>
        </thead>
        <tbody class="center ui-sortable">
           <input type="hidden" id="api_ems_ids" value="">

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
                    @foreach ($emsType->api_ems as $apiEmsKey => $apiEms)
                    <div class="checkbox-wrapper-28">
                        <input id="{{$apiEms->ems_name.'_'.$key.'_'.$apiEmsKey}}" type="checkbox" value="{{$apiEms->id}}" class="api_ems_id" {{$apiEms->rubric_template_id ? 'checked' : ''}}/>
                        <svg><use xlink:href="#checkmark_{{$apiEms->ems_name.'_'.$key.'_'.$apiEmsKey}}" /></svg>
                        <label for="{{$apiEms->ems_name.'_'.$key.'_'.$apiEmsKey}}">{{$apiEms->ems_name}}</label>
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none">
                          <symbol id="checkmark_{{$apiEms->ems_name.'_'.$key.'_'.$apiEmsKey}}" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-miterlimit="10" fill="none"  d="M22.9 3.7l-15.2 16.6-6.6-7.1">
                            </path>
                          </symbol>
                        </svg>
                    </div>
                    @endforeach
                 </div>
              </td>
           </tr>
           @endforeach
           @if ($emsTypes->isEmpty())
               <tr>
                    <td align="center">Không có dữ liệu</td>
               </tr>
           @endif
        </tbody>
     </table>
</div>

<div class="table-popup-rubric-template">
    <table class="table-popup table-bordered">
        <thead>
            <th style="text-align: center">Coures</th>
        </thead>
        <tbody class="center ui-sortable">
           <input type="hidden" id="api_moodle_ids" value="">
           <meta name="csrf-token" content="{{ csrf_token() }}">
           @foreach ($apiMooles as $key => $apiMoole)
            <tr data-toggle="collapse" style="cursor: pointer" data-target="#collapseCourse{{$key}}" aria-expanded="true">
                <td class="left bi_name">
                <div>{{$apiMoole->moodle_name}}</div>
                </td>
            </tr>
           <tr class="collapse show" id="collapseCourse{{$key}}">
              <td class="left bi_name">
                <div class="card card-body">
                    <div class="checkbox-wrapper-28">
                        <input id="{{$apiMoole->course->name.'_'.$key}}" type="checkbox" value="{{$apiMoole->id}}" class="api_moodle_id" {{$apiMoole->rubric_template_id ? 'checked' : ''}}/>
                        <svg><use xlink:href="#checkmark_{{$apiMoole->course->name.'_'.$key}}" /></svg>
                        <label for="{{$apiMoole->course->name.'_'.$key}}">{{$apiMoole->course->name}}</label>
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none">
                          <symbol id="checkmark_{{$apiMoole->course->name.'_'.$key}}" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-miterlimit="10" fill="none"  d="M22.9 3.7l-15.2 16.6-6.6-7.1">
                            </path>
                          </symbol>
                        </svg>
                    </div>
                </div>
              </td>
           </tr>
           @endforeach
           @if ($apiMooles->isEmpty())
               <tr>
                 <td align="center">Không có dữ liệu</td>
               </tr>
           @endif
        </tbody>
     </table>
</div>
