@extends('layouts.app')
@section('title' , '| Eργαζόμενοι')
@section('content')

    {{--{{ dd($data, $jobs) }}--}}
    {{--IsOnline, Se poio job einai (apo JobId), CurrentNumber, NumberStatus--}}
    <br><br>
    <h2 style="margin-left:70px;">Εργαζόμενοι</h2>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                    <thead>
                    <tr>
                        <th class="text-center">
                            TotalReservations
                        </th>
                        <th class="text-center">
                            UnattendedReservations
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id='addr0' data-id="0">

                        {{--<td data-name="name">--}}
                            {{--{{$tickets['name']}}--}}
                        {{--</td>--}}
                        <td data-name="mail">
                            {{$member['TotalReservations']}}
                        </td>
                        <td data-name="mail">
                            {{$member['UnattendedReservations']}}
                        </td>
                        <td data-name="mail">
                            {{$name}}
                        </td>
                        <td data-name="mail">
                            {{$email}}
                        </td>

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

