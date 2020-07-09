@extends('adminMaster')

@section('content')
<section class="content">
  <!-- Application Status tittle--> 
<h5 class="mt-4 mb-2">Status Permohonan</h5>
  <div class="card-body">
    <a href="{{ url()->previous() }}">
    <input type="button" id="btnback1" name="btnback1" value="&laquo;back" class="btn btn-light hBack"/>
    </a>
    <div class="form-group row">
      <label class="col-sm-2 col-form-label"></label>
    </div>
    <div style="height:700px; width:880px; border:none; overflow:scroll; overflow-x:hidden; overflow-y:scroll;">
      <p>
      <x-preview  :previewData=$prevData />
      </p>
    </div>
    <div class="form-group row">
      <label class="col-sm-2 col-form-label"></label>
    </div>
      <!-- Application Status table--> 
       <table class="table table-bordered table-sm" align="center" style="width:480px" >
        <tbody class="thead-dark">
          <tr>
            <th class="thcalculate" scope="col">No. Rujukan</th>
            <td class="tdtextboxalign"><input type="text" class="form-control" style="width:300px;background-color:#e9ecef" id="appreferenceno" name="appreferenceno" 
              readonly="readonly" value="{{ $review['refNo'] ?? '' }}"></td>
          </tr>
          <tr>
            <th class="thcalculate" scope="col">Status</th>
            <td class="tdtextboxalign"><input type="text" class="form-control" id="appstatus" name="appstatus" 
              style="width:300px;background-color:#e9ecef" readonly="readonly" value="{{ $review['Status'] ?? '' }}"></td>
          </tr>
          <tr>
            <th class="thcalculate" scope="col">Last Comment</th>
            <td class="tdtextboxalign">
              <textarea class="form-control" id="appremark" name="appremark" rows="4" cols="50" style="width:300px;background-color:#e9ecef"
              readonly="readonly" >{{ $review['comment'] ?? '' }}
              @isset($review['system_comment'])
              {{ $review['system_comment'] ?? '' }}
              @endisset
              </textarea>
            </td>
          </tr>
          <tr>
            <th class="thcalculate" scope="col">Comment By</th>
            <td class="tdtextboxalign"><input type="text" class="form-control" id="appby" name="appby" 
              style="width:300px;background-color:#e9ecef"  readonly="readonly" value="{{ $review['comment_by'] ?? '' }}"></td>
          </tr>
        </tbody>
      </table>
    </div>


    <div class="form-group row">
      <label class="col-sm-2 col-form-label"></label>
    </div>

    <form method="post" action="{{ url()->current()}}">
      @csrf

    <table class="table table-bordered " onchange="calculateAmount3(this.value3)" required >
      <thead class="thead-dark">
        <tr>
          <th class="align-text-top" scope="col">#</th>
          <th class="align-text-top" scope="col">PARTICIPATING COMPANY/INVESTOR</th>
          <th class="align-text-top" scope="col">WEIGHTAGE</th>
          <th class="align-text-top" scope="col">SCORE</th>
          <th class="align-text-top" scope="col">WEIGHTED SCORE</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Years in operation (may include the parent co)</td>
          <td>10%</td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign @if($domRights['scorecardWrite'] =='0') readonlycolor @endif"
          id="inputyear" name="inputyear" value="{{$review['inputyear'] ?? '0' }}" @if($domRights['scorecardWrite'] =='0') readonly @endif></td>
          <td><input type="number" class="textboxalign boldformula readonlycolor" name="totalyear" id="totalyear" readonly="readonly"></td>
        </tr>
        <tr>
          <td>2</td>
          <td>Number of staff (including forecast for next 3 years)</td>
          <td>10%</td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign @if($domRights['scorecardWrite'] =='0') readonlycolor @endif"
            id="inputstaff" name="inputstaff" value="{{$review['inputstaff'] ?? '0' }}" @if($domRights['scorecardWrite'] =='0') readonly @endif></td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign readonlycolor"
            id="totalstaff" name="totalstaff" readonly="readonly" ></td>
        </tr>
        <tr>
          <td>3</td>
          <td>Fiscal health (e.g. companies with 3 or more years of consecutive losses may not be considered) -may consider group fiscal health</td>
          <td>10%</td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign @if($domRights['scorecardWrite'] =='0') readonlycolor @endif" 
            id="inputfiscal" name="inputfiscal" value="{{$review['inputfiscal'] ?? '0' }}" @if($domRights['scorecardWrite'] =='0') readonly @endif></td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign readonlycolor"
            id="totalfiscal" name="totalfiscal" readonly="readonly" ></td>
        </tr>
        <tr>
          <td>4</td>
          <td>Technical capability and know-how of the business/industry (e.g. MTS index)</td>
          <td>20%</td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign @if($domRights['scorecardWrite'] =='0') readonlycolor @endif"
            id="inputtechnical" name="inputtechnical" value="{{$review['inputtechnical'] ?? '0' }}" @if($domRights['scorecardWrite'] =='0') readonly @endif></td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign readonlycolor"
            id="totaltechnical" name="totaltechnical" readonly="readonly" ></td>
        </tr>
        <tr>
          <td>5</td>
          <td>Training Programme - including structured training plan, softskills and hardskills, trainers qualification, training needs analysis, certification type  </td>
          <td>25%</td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign @if($domRights['scorecardWrite'] =='0') readonlycolor @endif"
            id="inputtraining" name="inputtraining" value="{{$review['inputtraining'] ?? '0' }}" @if($domRights['scorecardWrite'] =='0') readonly @endif></td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign readonlycolor"
            id="totaltraining" name="totaltraining" readonly="readonly" ></td>
        </tr>
        <tr>
          <td>6</td>
          <td>Value proposition to NCER (e.g. benefits to the rakyat, hiring unemployed graduates, retrenched workers, contribution to the ecosystem etc.)</td>
          <td>25%</td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign @if($domRights['scorecardWrite'] =='0') readonlycolor @endif"
            id="inputncer" name="inputncer" value="{{$review['inputncer'] ?? '0' }}" @if($domRights['scorecardWrite'] =='0') readonly @endif></td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign readonlycolor"
            id="totalncer" name="totalncer" readonly="readonly" ></td>
        </tr>
        <tr>
          <th style="text-align: right" colspan="2">Total</th>
          <td>100%</td>
          <td style="background-color: black"></td>
          <td><input type="number" min=0 oninput="validity.valid||(value='');" class="textboxalign readonlycolor"
            id="total" name="total" readonly="readonly" ></td>
        </tr>
      </tbody>
    </table>


      <div class="form-group row">
        <label class="col-sm-3 col-form-label"></label>
        <label class="col-sm-2 col-form-label">Approved Pax</label>
        <div class="col-sm-4">
        <input type="number" class="form-control @if($domRights['fundingWrite'] =='0') readonlycolor @endif" 
        step="1" @if($domRights['fundingWrite'] =='0') readonly @endif
        id="inputPax" name="inputPax" value="{{ $review['approved_pax'] ?? '' }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-3 col-form-label"></label>
        <label class="col-sm-2 col-form-label">Approved Fund</label>
        <div class="col-sm-4">
          <input type="number" class="form-control @if($domRights['fundingWrite'] =='0') readonlycolor @endif" 
          step="0.1" @if($domRights['fundingWrite'] =='0') readonly @endif
          id="inputFund" name="inputFund" value="{{ $review['approved_fund'] ?? '' }}">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-1 col-form-label"></label>
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-4">

          <textarea id="comment" name="comment"
          rows="5" cols="65" placeholder="Type in your comment here..."></textarea>
        </div>
      </div>
    </textarea>
    <div class="form-group row">
      <label class="col-sm-3"></label>
      @isset( $review['backward_action'])
      <button type="submit" id="btnReject" name="btnReject" value="{{$review['backward_action']}}" class="btn btn-danger">
        Reject</button>
      @endisset
      <label class="col-sm-4"></label> 
      <div class="col-sm-4">
        @isset( $review['forward_action'])
      <button type="submit" id="btnForward" name="btnForward" value="{{$review['forward_action']}}" class="btn btn-success">
          Proceed</button>
        @endisset
      </div>
    </div>
    </form>
      
  <!-- /.card-body -->
  <div class="card-footer">
    <a href="{{ url()->previous() }}">
      <input type="button" id="btnback2" name="btnback" value="&laquo;back" class="btn btn-light hBack"/>
    </a>
  </div>
  <!-- /.card-footer -->

</section>
@endsection
@section('jsscript2')
<script>
  function calculateAmount3(val3) {
      let num1 = document.getElementsByName("inputyear")[0].value;
      let totalyear = (0.1 * (Number(num1) / 5 )) * 100;
      document.getElementsByName("totalyear")[0].value = totalyear.toFixed(2);
      
      let num2 = document.getElementsByName("inputstaff")[0].value;
      let totalstaff = (0.1 * (Number(num2) / 5 )) * 100;
      document.getElementsByName("totalstaff")[0].value = totalstaff.toFixed(2);

      let num3 = document.getElementsByName("inputfiscal")[0].value;
      let totalfiscal = (0.1 * (Number(num3) / 5 )) * 100;
      document.getElementsByName("totalfiscal")[0].value = totalfiscal.toFixed(2);

      let num4 = document.getElementsByName("inputtechnical")[0].value;
      let totaltechnical = (0.2 * (Number(num4) / 5 )) * 100;
      document.getElementsByName("totaltechnical")[0].value = totaltechnical.toFixed(2);

      let num5 = document.getElementsByName("inputtraining")[0].value;
      let totaltraining = (0.25 * (Number(num5) / 5 )) * 100;
      document.getElementsByName("totaltraining")[0].value = totaltraining.toFixed(2);

      let num6 = document.getElementsByName("inputncer")[0].value;
      let totalncer = (0.25 * (Number(num6) / 5 )) * 100;
      document.getElementsByName("totalncer")[0].value = totalncer.toFixed(2);

      let grandtotal = totalyear + totalstaff + totalfiscal + totaltechnical + totaltraining + totalncer;
      document.getElementsByName("total")[0].value = grandtotal.toFixed(2);
  }

  $(document).ready(function() {
    calculateAmount3(0);
  });
</script>
@endsection














