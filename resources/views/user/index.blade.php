@extends('layouts.template')

@section('content')
<div>
    <h3 class="text-base font-semibold leading-6 text-gray-900">User</h3>
    <div class="flex justify-between items-start space-x-3 mt-5">
        <button data-modal-target="create-modal" data-modal-toggle="create-modal" type="submit" class="bg-white text-gray-700 px-4 text-sm font-semibold py-3 rounded-md border border-gray-300 hover:bg-gray-100">
            Add User
        </button>
        <form action="{{ route("user.index") }}" method="GET">
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
                    <th scope="col" class="px-4 py-3">
                        Nama
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Username
                    </th>
                    <th scope="col" class="px-4 py-3">
                        NIS
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Rayon
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Position
                    </th>
                    <th scope="col" class="px-4 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @if (!$users->isEmpty())
                @foreach ($users as $item)
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-5 py-5 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->name }}
                    </th>
                    <td class="px-5 py-5">
                        {{ $item->username }}
                    </td>
                    <td class="px-5 py-5">
                        {{ $item->nis }}
                    </td>
                    <td class="px-5 py-5">
                        {{ $item->rayon}}
                    </td>
                    <td class="px-5 py-5">
                        {{ $item->position }}
                    </td>
                    <td class="px-5 py-5 lg:space-x-1">
                        <a href="data:image/png;base64,{{DNS2D::getBarcodePNG($item->uuid, 'QRCODE',30,30)}}" download="{{ $item->name }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">Print QR</a>
                        <button data-modal-target="edit-modal-{{ $item->uuid }}" data-modal-toggle="edit-modal-{{ $item->uuid }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                        <button id="deleteButton-{{ $item->uuid }}" type="button" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                        <form action="{{ route('user.delete', $item->uuid) }}" method="POST" id="deleteForm" hidden>
                            @csrf
                            @method("DELETE")
                        </form>
                    </td>
                </tr>
                @endforeach
                @else
                    <td colspan="7" class="text-center text-xl text-bold mt-2 py-5">No Users Found</td>
                @endif
            </tbody>
        </table>
        @if (!$users->isEmpty())
        <div class="p-5">
            {{ $users->links() }}
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
                        Create User
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="create-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('user.store') }}" class="p-4 md:p-5" method="POST">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Name" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="nis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIS</label>
                            <input type="number" name="nis" id="nis" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="NIS" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rayon</label>
                            <input type="text" name="rayon" id="rayon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rayon" required="">
                        </div>
                        <div class="col-span-2">
                            <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                            <input type="text" name="position" id="position" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Position" required="">                 
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

    @if (!$users->isEmpty())
    @foreach ($users as $item)

    {{-- Start Edit Modal --}}
    <!-- Main modal -->
    <div id="edit-modal-{{ $item->uuid }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit User
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-{{ $item->uuid }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('user.update', $item->uuid)}}" class="p-4 md:p-5" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Name" required="" value="{{ $item->name }}">
                        </div>
                        <div class="col-span-2">
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Username" required="" value="{{ $item->username }}">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="nis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIS</label>
                            <input type="number" name="nis" id="nis" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="NIS" required="" value="{{ $item->nis }}">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rayon</label>
                            <input type="text" name="rayon" id="rayon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rayon" required="" value="{{ $item->rayon }}">
                        </div>
                        <div class="col-span-2">
                            <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                            <input type="text" name="position" id="position" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Position" required="" value="{{ $item->position }}">                 
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
    <script>
        document.getElementById('deleteButton-{{ $item->uuid }}').addEventListener('click', function() {
            Swal.fire({
                title: "Are you sure you want to delete this user?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, I'm sure"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        });
    </script>
    {{-- End Delete Modal --}}
    @endforeach
    @endif
</div>
@endsection