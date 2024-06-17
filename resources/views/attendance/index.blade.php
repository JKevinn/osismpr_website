@extends('layouts.template')

@section('content')
<div>
    <h3 class="text-base font-semibold leading-6 text-gray-900">Attendance</h3>

    <div class="flex justify-end items-start lg:space-x-3 mt-5">
        <form action="{{ route("attendance.index") }}" method="GET">
            <div class="max-w-lg mx-auto">
                <div class="flex space-x-2">
                    <div class="flex w-full rounded-md overflow-hidden">
                        <input type="text" name="search" class="w-full p-2 rounded-l-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search..." />
                        <button class="bg-blue-600 text-white px-4 text-sm font-semibold py-2 rounded-r-md hover:bg-blue-700">Go</button>
                    </div>
                    <button type="submit" class="bg-white text-gray-700 px-4 text-sm font-semibold py-2 rounded-md border border-gray-300 hover:bg-gray-100">Clear</button>
                </div>
            </div>
        </form>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Meeting Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Location
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Attendance
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
            @if (!$meetings->isEmpty())
                @php
                    $previousDate = null;
                @endphp
                @foreach ($meetings as $item)
                @if ($item->date != $previousDate)
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" colspan="5"><span class="font-medium text-red-500 border-2 border-red-500 p-1 rounded-lg">{{ $item->date }}</span></th>
                </tr>
                @php
                    $previousDate = $item->date;
                @endphp
                @endif
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->meeting_title }}
                    </th>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($item->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->time_end)->format('H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->location }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->attendances_count }}
                    </td>
                    <td class="px-6 py-4 lg:space-x-3">
                        <a href="{{ route('attendance.listAttendance', $item->uuid)}}" class="font-medium text-green-600 dark:text-green-500 hover:underline">View Detail</a>
                        @if ($item->status == "upcoming")
                            <a href="{{ route('attendance.scanner', $item->uuid)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Scan QR</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center text-xl text-bold mt-2 py-5">No Attendance Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if (!$meetings->isEmpty())
            <div class="p-5">
                {{ $meetings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection