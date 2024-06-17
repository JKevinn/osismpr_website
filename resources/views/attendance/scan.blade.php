@extends('layouts.template')

@section('content')
<div>
    <h3 class="text-base font-semibold leading-6 text-gray-900">Scan QR Code</h3>
    <div class="flex justify-between items-start space-x-3 mt-5">
        <a href="{{ route('attendance.index') }}" class="bg-white text-gray-700 px-4 text-sm font-semibold py-3 rounded-md border border-gray-300 hover:bg-gray-100">
            Back
        </a>
    </div>
    <div class="flex flex-wrap justify-center xl:justify-between">
        <div class="mt-5 relative shadow-md sm:rounded-lg max-w-[300px] max-h-fit">
            <video id="preview" class="p-2"></video>
            <form action="{{ route('attendance.scan', $meeting_uuid) }}" method="POST" id="form">
                @csrf
                <input type="hidden" name="uuid" id="uuid">
            </form>
        </div>
        <div class="mt-5 relative shadow-md sm:rounded-lg w-full xl:w-[70%]">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Arrive At
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$attendance->isEmpty())
                    @foreach ($attendance as $item)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $item->name }}
                        </th>
                        @if ($item->status == "on_time")
                        <td class="px-6 py-4 text-green-500">
                            {{ $item->arrival_time }}
                        </td>
                        <td class="px-6 py-4 text-green-500">
                            On Time
                        </td>
                        @else
                        <td class="px-6 py-4 text-red-500">
                            {{ $item->arrival_time }}
                        </td>
                        <td class="px-6 py-4 text-red-500">
                            Late
                        </td>
                        @endif
                    </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-xl text-bold mt-2 py-5">Nobody has arrived yet</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript">
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    const modal = document.getElementById('medium-modal');
    console.log(modal);
    

    scanner.addListener('scan', function (content) {
        console.log(content);
    });
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
        scanner.start(cameras[0]);
        } else {
        console.error('No cameras found.');
        }
    }).catch(function (e) {
        console.error(e);
    });

    scanner.addListener('scan', function(c){
        document.getElementById('uuid').value = c;
        document.getElementById('form').submit();
    })

</script>
@endsection