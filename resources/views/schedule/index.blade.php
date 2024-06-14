@extends('layouts.template')

@section('content')
<div>
    @if (Session::has("success"))
        <script>
        Swal.fire({
            icon: "success",
            title: `{{ Session::get("success")}}` ,
            showConfirmButton: false,
            timer: 1500
        });
        </script>
    @endif
    @if (Session::has("failed"))
        <script>
        Swal.fire({
            icon: "error",
            title: `{{ Session::get("failed")}}` ,
            showConfirmButton: false,
            timer: 1500
        });
        </script>
    @endif
    @if (Session::has("error"))
        <script>
        Swal.fire({
            icon: "error",
            title: `{{ Session::get("error")}}` ,
            showConfirmButton: false,
            timer: 1500
        });
        </script>
    @endif
    <h3 class="text-base font-semibold leading-6 text-gray-900">Schedule</h3>
    <div class="flex justify-between items-start lg:space-x-3 mt-5">
        <button data-modal-target="create-modal" data-modal-toggle="create-modal" type="submit" class="bg-white text-gray-700 px-4 text-sm font-semibold py-3 rounded-md border border-gray-300 hover:bg-gray-100 hidden md:block">
            Add Meeting
        </button>
        <button data-modal-target="create-modal" data-modal-toggle="create-modal" type="submit" class="bg-white text-gray-700 px-4 text-sm font-semibold py-3 rounded-md border border-gray-300 hover:bg-gray-100 block md:hidden">
            Add
        </button>
        <form action="{{ route("schedule.index") }}" method="GET">
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
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Created By
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @if (!$schedule->isEmpty())
                    @php
                        $previousDate = null;
                    @endphp
                    @foreach ($schedule as $item)
                        @if ($item->date != $previousDate)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" colspan="7">
                                    <span class="font-medium text-red-500 border-2 border-red-500 p-1 rounded-lg">{{ $item->date }}</span>
                                </th>
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
                            <td class="px-6 py-4 max-w-64">
                                {{ $item->description }}
                            </td>
                            <td class="px-6 py-4 max-w-64">
                                {{ $item->created_by }}
                            </td>
                            <td class="px-6 py-4 max-w-64">
                                @if ($item->status == "upcoming")
                                <span class="text-white bg-orange-400 p-2 font-semibold rounded-lg">
                                    {{ ucfirst($item->status) }}
                                </span>
                                @elseif ($item->status == "completed")
                                <span class="text-white bg-green-500 p-2 font-semibold rounded-lg">
                                    {{ ucfirst($item->status) }}
                                </span>
                                @else
                                <span class="text-white bg-red-500 p-2 font-semibold rounded-lg">
                                    {{ ucfirst($item->status) }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 lg:space-x-3">
                                <button data-modal-target="edit-modal-{{ $item->uuid }}" data-modal-toggle="edit-modal-{{ $item->uuid }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                                <button data-modal-target="delete-modal-{{ $item->uuid }}" data-modal-toggle="delete-modal-{{ $item->uuid }}" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center text-xl text-bold mt-2 py-5">No Meeting Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if (!$schedule->isEmpty())
            <div class="p-5">
                {{ $schedule->links() }}
            </div>
        @endif
    </div>

    {{--Start Create Modal --}}
    <!-- Main modal -->
    <div id="create-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Create Meeting
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="create-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('schedule.store') }}" class="p-4 md:p-5" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="meeting_title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Meeting Title</label>
                            <input type="text" name="meeting_title" id="meeting_title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Meeting Title" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="location" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>
                            <input type="text" name="location" id="location" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Location" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                            <input type="date" name="date" id="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="time_start" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time Start</label>
                            <input type="time" name="time_start" id="time_start" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="time_end" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time End</label>
                            <input type="time" name="time_end" id="time_end" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write description here"></textarea>                    
                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
    {{-- End Create Modal --}}
    @if (!$schedule->isEmpty())
    @foreach ($schedule as $item)    

    {{-- Start Edit Modal --}}

    <!-- Main modal -->
    <div id="edit-modal-{{ $item->uuid }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit Meeting
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-{{ $item->uuid }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('schedule.update', $item->uuid) }}" class="p-4 md:p-5" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an option</label>
                            <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected hidden value="{{ $item->status }}">{{ ucfirst($item->status) }}</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="completed">Completed</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label for="meeting_title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Meeting Title</label>
                            <input type="text" name="meeting_title" id="meeting_title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Meeting Title" required="" value="{{ $item->meeting_title }}">
                        </div>
                        <div class="col-span-2">
                            <label for="location" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>
                            <input type="text" name="location" id="location" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Location" required="" value="{{ $item->location }}">
                        </div>
                        <div class="col-span-2">
                            <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                            <input type="date" name="date" id="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="" value="{{ $item->date }}">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="time_start" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time Start</label>
                            <input type="time" name="time_start" id="time_start" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="" value="{{ $item->time_start }}">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="time_end" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Time End</label>
                            <input type="time" name="time_end" id="time_end" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="" value="{{ $item->time_end}}">
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write description here">{{ $item->description }}</textarea>                    
                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
    {{-- End Edit Modal --}}

    {{-- Start Delete Modal --}}
    <div id="delete-modal-{{ $item->uuid }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-modal-{{ $item->uuid }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <form action="{{ route('schedule.delete', $item->uuid)}}" class="p-4 md:p-5 text-center" method="POST">
                    @csrf
                    @method("DELETE")
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this meeting?</h3>
                    <button data-modal-hide="delete-modal-{{ $item->uuid }}" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Yes, I'm sure
                    </button>
                    <button data-modal-hide="delete-modal-{{ $item->uuid }}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                </form>
            </div>
        </div>
    </div>
    {{-- End Delete Modal --}}
    @endforeach
    @endif
</div>
@endsection