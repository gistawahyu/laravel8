@extends('main')

@section('title','Data Nilai Karyawan')

@section('breadcrumbs')
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Data Nilai Mingguan</h1>
                    </div>
                </div>
            </div>			
        </div>
@endsection

@section('content')
        <div class="content mt-3">
            <div class="animated fadeIn">

            	@if (session('status'))
    				<div class="alert alert-success">
        				{{ session('status') }}
    				</div>
			@endif

               <div class="card">
               	<div class="card-header">
               		<div class="pull-left">
               			<strong>Data Nilai Mingguan</strong>
               		</div>
               		
               	</div>
               	<div class="card-body table-responsive">
               		<table class="table table-bordered">
               		<thead>
               			<tr class="text-center">
               			<th>No</th>
               		    <th>Nama Karyawan</th>
                      <th>Wilayah Samchick</th>
                      <th>Tanggal</th>
                      <th>Nilai Artikel</th>
                      <th>Nilai Share WhatsApp</th>
                      <th>Nilai Share Pamflet</th>
                      <th>Total Nilai</th>
               			</tr>
               		</thead>
                  <tbody>
                  @php
                  $i = 1;
                  @endphp
                     @foreach ($mingguan as $key => $item)
                    <tr>
                      <td class="text-center">{{ $i++ }}</td>
                      <td class="text-center">{{ $item->name }}</td>
                      <td class="text-center">{{ $item->namacbg }}</td>
                      <td class="text-center">{{ $item->tgl }}</td>
                      <td class="text-center">{{$item->ar}}</td>
                      <td class="text-center">{{$item->wa}}</td>
                      <td class="text-center">{{$item->pam}}</td>
                      <td class="text-center">{{ $item->total }}</td>
                      
                    </tr>
                    @endforeach 
                  </tbody>
               </table>
               <div class="pull-left">
                 
               	</div>
               </div>
            </div>
        </div>
    </div>
@endsection