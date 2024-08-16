@extends('layouts.template')

@section('content')
<div>
    <h3 class="text-base font-semibold leading-6 text-gray-900">Dashboard</h3>
  
    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <div class="relative overflow-hidden rounded-lg bg-gray-600 px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
        <dt>
          <div class="absolute rounded-md bg-red-600 p-3">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="User / Users"> <path id="Vector" d="M21 19.9999C21 18.2583 19.3304 16.7767 17 16.2275M15 20C15 17.7909 12.3137 16 9 16C5.68629 16 3 17.7909 3 20M15 13C17.2091 13 19 11.2091 19 9C19 6.79086 17.2091 5 15 5M9 13C6.79086 13 5 11.2091 5 9C5 6.79086 6.79086 5 9 5C11.2091 5 13 6.79086 13 9C13 11.2091 11.2091 13 9 13Z" stroke="currentColour" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>
          </div>
          <p class="ml-16 truncate text-sm font-medium text-gray-300">Total Users</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
          <p class="text-2xl font-semibold text-gray-100">{{ $users }}</p>
          <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
            <div class="text-sm">
              <a href="{{ route('user.index') }}" class="font-medium text-orange-400 hover:text-red-500">View all<span class="sr-only"> Total Users</span></a>
            </div>
          </div>
        </dd>
      </div>
      <div class="relative overflow-hidden rounded-lg bg-gray-600 px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
        <dt>
          <div class="absolute rounded-md bg-orange-500 p-3">
            <svg class="h-6 w-6 text-white" fill="currentColor" height="200px" width="200px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M490.667,42.667H380.075C371.243,17.899,347.776,0,320,0s-51.243,17.899-60.075,42.667h-50.517 C200.576,17.899,177.109,0,149.333,0S98.091,17.899,89.259,42.667H21.333C9.536,42.667,0,52.203,0,64v85.333h512V64 C512,52.203,502.464,42.667,490.667,42.667z"></path> <path d="M0,490.667C0,502.464,9.536,512,21.333,512h469.333c11.797,0,21.333-9.536,21.333-21.333V192H0V490.667z M341.333,448 H234.667c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h106.667c11.797,0,21.333,9.536,21.333,21.333 S353.131,448,341.333,448z M426.667,448h-21.333C393.536,448,384,438.464,384,426.667s9.536-21.333,21.333-21.333h21.333 c11.797,0,21.333,9.536,21.333,21.333S438.464,448,426.667,448z M426.667,362.667h-21.333c-11.797,0-21.333-9.536-21.333-21.333 c0-11.797,9.536-21.333,21.333-21.333h21.333c11.797,0,21.333,9.536,21.333,21.333C448,353.131,438.464,362.667,426.667,362.667z M362.667,234.667h64c11.797,0,21.333,9.536,21.333,21.333s-9.536,21.333-21.333,21.333h-64 c-11.797,0-21.333-9.536-21.333-21.333S350.869,234.667,362.667,234.667z M362.667,341.333c0,11.797-9.536,21.333-21.333,21.333 H234.667c-11.797,0-21.333-9.536-21.333-21.333c0-11.797,9.536-21.333,21.333-21.333h106.667 C353.131,320,362.667,329.536,362.667,341.333z M192,234.667h106.667c11.797,0,21.333,9.536,21.333,21.333 s-9.536,21.333-21.333,21.333H192c-11.797,0-21.333-9.536-21.333-21.333S180.203,234.667,192,234.667z M85.333,234.667H128 c11.797,0,21.333,9.536,21.333,21.333s-9.536,21.333-21.333,21.333H85.333C73.536,277.333,64,267.797,64,256 S73.536,234.667,85.333,234.667z M85.333,320h85.333c11.797,0,21.333,9.536,21.333,21.333c0,11.797-9.536,21.333-21.333,21.333 H85.333c-11.797,0-21.333-9.536-21.333-21.333C64,329.536,73.536,320,85.333,320z M85.333,405.333h85.333 c11.797,0,21.333,9.536,21.333,21.333S182.464,448,170.667,448H85.333C73.536,448,64,438.464,64,426.667 S73.536,405.333,85.333,405.333z"></path> </g> </g> </g> </g></svg>
          </div>
          <p class="ml-16 truncate text-sm font-medium text-gray-300">Upcoming Meetings</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
          <p class="text-2xl font-semibold text-gray-100">{{ $upcomingMeeting }}</p>
          <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
            <div class="text-sm">
              <a href="{{ route('schedule.index') }}" class="font-medium text-yellow-600 hover:text-orange-500">View all<span class="sr-only"> Upcoming Meetings</span></a>
            </div>
          </div>
        </dd>
      </div>
      <div class="relative overflow-hidden rounded-lg bg-gray-600 px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
        <dt>
          <div class="absolute rounded-md bg-blue-500 p-3">
            <svg class="h-7 w-7 text-white" fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M490.667,21.337h-128H21.333C9.536,21.337,0,30.894,0,42.67v85.333h512V42.67C512,30.894,502.464,21.337,490.667,21.337z"></path> </g> </g> <g> <g> <path d="M0,170.663V469.33c0,11.797,9.536,21.333,21.333,21.333h469.333c11.797,0,21.333-9.536,21.333-21.333V170.663H0z M359.082,267.836l-85.333,128c-3.541,5.333-9.301,8.768-15.637,9.387c-0.725,0.085-1.429,0.107-2.112,0.107 c-5.632,0-11.072-2.219-15.083-6.251l-64-64c-8.341-8.32-8.341-21.824,0-30.165c8.341-8.32,21.824-8.32,30.165,0l45.611,45.589 l70.891-106.325c6.507-9.813,19.733-12.501,29.589-5.931C362.986,244.775,365.632,258.044,359.082,267.836z"></path> </g> </g> </g></svg>
          </div>
          <p class="ml-16 truncate text-sm font-medium text-gray-300">Completed Meetings</p>
        </dt>
        <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
          <p class="text-2xl font-semibold text-gray-100">{{ $completedMeeting }}</p>
          <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
            <div class="text-sm">
              <a href="{{ route('schedule.index') }}" class="font-medium text-cyan-600 hover:text-green-500">View all<span class="sr-only"> Completed Meetings</span></a>
            </div>
          </div>
        </dd>
      </div>
    </dl>
  </div>
@endsection