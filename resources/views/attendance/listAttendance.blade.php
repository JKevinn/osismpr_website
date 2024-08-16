@extends('layouts.template')

@section('content')
<div>
    <h3 class="text-base font-semibold leading-6 text-gray-900">List Attendance</h3>
    <div class="flex justify-between items-start space-x-3 mt-5">
        <a href="{{ route('attendance.index') }}" class="bg-white text-gray-700 px-4 text-sm font-semibold py-3 rounded-md border border-gray-300 hover:bg-gray-100">
            Back
        </a>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Arrival Time
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
                            {{ $item->user->name }}
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
                        <td colspan="5" class="text-center text-xl text-bold mt-2 py-5">No Attendance Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if (!$attendance->isEmpty())
            <div class="p-5">
                {{ $attendance->links() }}
            </div>
        @endif
    </div>
</div>
@endsection