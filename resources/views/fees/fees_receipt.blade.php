<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fees Receipt For {{$student->user->full_name}} </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container-fluid">
        <div class="row mt-4 mx-2 px-2">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <div class="text-center">
                            @if(!empty($schoolVerticalLogo) || isset($schoolVerticalLogo))
                            <i><img style="height: 5rem" src="{{public_path('storage/'.$schoolVerticalLogo->getRawOriginal('data'))}}" alt="logo"></i><br>
                            @else
                            <i><img style="height: 5rem;" src="{{public_path('storage/'.$systemVerticalLogo->getRawOriginal('data'))}}" alt="logo"></i><br>
                            @endif
                            <span class="text-default-d3 ml-4 text-info" style="font-size:1.5rem"><strong>{{$school['school_name'] ?? ''}}</strong></span><br>
                            <span class="text-default-d3 ml-4 text-center" style="font-size:1rem">{{$school['school_address'] ?? ''}}</span>
                            <h4 class="text-center">Fee Receipt</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    </div>

                    <div class="col-sm-6 align-self-start d-sm-flex justify-content-end">
                        <div class="text-grey-m2 mt-2 ml-3">
                            <p><strong><u>Invoice</u></strong><br>
                                <strong>Fee Statement</strong> :- {{$feesPaid->id ?? ''}}<br>
                            </p>
                        </div>
                    </div>
                </div>
                <hr style="border: 1px solid">
                <div class="row ml-3">
                    <div class="col-sm-6 align-self-start">
                        <div class="row text-black">
                                <p><strong><u>Student Details :- </u></strong><br>
                                <strong>Student Name</strong> : {{$student->user->full_name}} <br>
                                {{-- <strong>Session</strong> : {{isset($feesPaid) ? $feesPaid->session_year->name : '-'}} <br>--}}
                                <strong>Class</strong> : {{$feesPaid->fees->class->full_name ?? ''}}<br>
                        </div>
                    </div>
                </div>
                <div class="mt-4 ml-2">
                    <table class="table table-bordered table-stripped table-xs" style="text-align: center">
                        <thead>
                            <tr>
                                <th scope="col">S/no.</th>
                                <th scope="col" colspan="3">Description</th>
                                <th scope="col">R. No.</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        @php
                        $no = 1;
                        @endphp
                        <tbody>
                            @php
                            $compulsoryFeesType = $feesPaid->fees->compulsory_fees->pluck('fees_type_name');
                            $compulsoryFeesType = implode(" , ",$compulsoryFeesType->toArray());
                            @endphp
                            {{--Compulsory Fees Listing --}}
                            @if(isset($feesPaid->compulsory_fee) && $feesPaid->compulsory_fee->isNotEmpty())
                            @foreach ($feesPaid->compulsory_fee as $compulsoryFee)
                            @if($compulsoryFee->type == "Installment Payment")
                            <tr>
                                <th scope="row" class="text-left">{{$no++}}</th>
                                <td colspan="3" class="text-left">{{$compulsoryFee->installment_fee->name}}</td>
                                <td class="text-right">{{$compulsoryFee->cheque_no}}</td>
                                <td class="text-right">{{$compulsoryFee->amount}} {{$school['currency_symbol'] ?? ''}}</td>
                                <td class="text-right"><small>{{$compulsoryFee->created_at->format('d-m-y H:m')}}</small></td>
                            </tr>
                            @endif
                            @endforeach
                            @endif

                            {{-- Optional Fees Listing --}}
                            @if(isset($feesPaid->optional_fee) && $feesPaid->optional_fee->isNotEmpty())
                            @foreach ($feesPaid->optional_fee as $optionalFee)
                            <tr>
                                <th scope="row" class="text-left">{{$no++}}</th>
                                <td colspan="2" class="text-left">{{ $optionalFee->fees_class_type->fees_type_name}} <small class="font-weight-bold">({{__("optional")}})</small>
                                    <br><small>Mode : <span class="font-weight-bold">({{ $optionalFee->mode_name }})</span></small>
                                    <br><small>Date &nbsp;: <span class="font-weight-bold">{{date('d-m-Y',strtotime($optionalFee->date))}} </span></small>
                                </td>
                                <td class="text-right">{{$optionalFee->amount}} {{$school['currency_symbol'] ?? ''}}</td>
                            </tr>
                            @endforeach
                            @endif


                            <tr>
                                <td colspan="6" class="text-left"><strong>Total Amount Expected</strong></td>
                                <td colspan="1" class="text-right">{{ number_format($feesPaid->fees->total_compulsory_fees,2)}} {{$school['currency_symbol'] ?? ''}}</td>

                            </tr>

                            <tr>
                                <td colspan="6" class="text-left"><strong>Total Amount Paid</strong></td>
                                <td colspan="1" class="text-right">{{ number_format($feesPaid->amount, 2)}} {{$school['currency_symbol'] ?? ''}}</td>

                            </tr>
                            @if(!$feesPaid->is_fully_paid)
                            <tr>
                                <td colspan="6" class="text-left"><strong>Balance</strong></td>
                                <td colspan="1" class="text-right">{{$feesPaid->fee_balance}} {{$school['currency_symbol'] ?? ''}}</td>

                            </tr>
                            @else
                            <tr>
                                <td colspan="7">
                                    <div>
                                        <h2 class="text-success" style="transform: rotate(-23deg);">CLEARED</h3>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
