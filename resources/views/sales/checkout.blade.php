@extends("shared.master")

@section('title', 'BiteScan | Checkout')

@section('content')
<div class="w-full h-[100vh] overflow-hidden flex">
  <div class="w-[75%]">
    <div class="header px-10 h-20 leading-[80px] flex justify-between">
      <div class="w-[20%]">BiteScan</div>
      <div class="flex items-center w-[80%] justify-end">

        <form class="w-[80%]">
          <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
          <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
              <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
              </svg>
            </div>
            <input type="search" id="search" autocomplete="off"
              class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-[var(--primary)] focus:border-[var(--primary)]"
              placeholder="Search for something..." />
            <button type="submit"
              class="text-white absolute end-2.5 bottom-2.5 bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-[var(--primary-dark)] font-medium rounded-lg text-sm px-4 py-2 ">Search</button>
          </div>
        </form>

      </div>
    </div>

    <hr>

    <div class="px-10">
      <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" role="tablist">
          @foreach($tabs as $index => $tab)
          <li class="me-2" role="presentation">
            <button
              class="inline-block p-4 border-b-2 rounded-t-lg {{ $index === 0 ? 'text-[var(--primary)] border-[var(--primary)]' : 'hover:text-[var(--primary)] hover:border-[var(--primary)]' }}"
              id="{{ $tab['id'] }}-tab" data-tab-category="{{ $tab['category'] }}"
              type="button" role="tab" aria-controls="{{ $tab['id'] }}"
              aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
              {{ $tab['title'] }}
            </button>
          </li>
          @endforeach
        </ul>
      </div>

      <div id="default-tab-content">
        @foreach($tabs as $index => $tab)
        <div class="rounded-lg bg-gray-50 grid grid-cols-7 gap-4 {{ $index === 0 ? '' : 'hidden' }}" id="{{ $tab['id'] }}" role="tabpanel">
          @foreach($orderItems as $item)
          @if($tab['category'] === 'all' || $item['category'] === $tab['category'])
          <div class="product-container " data-category="{{ $item['category'] }}">
            <div class="product-item cursor-pointer bg-white h-[270px]" data-product-image="{{$item['image']}}" data-product-id="{{ $item['id'] }}" data-product-name="{{ $item['name'] }}" data-product-price="{{ $item['price'] }}">
              <div class="product-image w-full flex justify-center h-[140px]">
                  <img src="{{$item['image']}}" alt="" class="h-full">
              </div>
              <div class="p-4 ">
              <div class="product-category line-clamp-2 break-words text-xs uppercase">{{ $item['category'] }}</div>
                <div class="product-name line-clamp-2 break-words font-bold">{{ $item['name'] }}</div>
                <div class="product-price">Php {{ number_format($item['price'], 2) }}</div>
              </div>
            </div>
          </div>
          @endif
          @endforeach
        </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="w-[25%] bg-white relative flex flex-col">
    <div class="order-items flex flex-col gap-4 h-[85%]">

      <div class="header border-b-2 h-[10%] px-4 py-2">
        <h1 class="text-2xl font-medium">Ordered Item(s)</h1>
        <p id="datetime" class="text-sm text-gray-500"></p>

      </div>

      <div class="w-full  px-4 h-[90%] overflow-y-auto">

        <div id="order-container" class="order-container h-full">
          <div id="empty-message" class="text-center text-gray-300 h-full flex items-center justify-center flex-col text-xl">
          <svg class="w-20 h-20 text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
</svg>

            No orders yet
          </div>
        </div>

      </div>
      <div class="order-summary border-t-2 border-dashed pt-4 px-4">
        <span class="text-2xl font-bold flex justify-between">
          <div>Total</div>
          <div>Php <span id="overall-total">0</span></span></div> 
      </div>
      <div class="action-btn w-full absolute bottom-[2rem] flex justify-between gap-4 px-5">
        <button type="submit"  id="cancel-order"
          class="cursor-pointer text-[var(--primary)] bg-[#f7f7f7] active:bg-[var(--primary)] active:text-white focus:outline-none focus:ring-[#f7f7f7] font-medium rounded-lg text-lg py-2 w-1/2 h-11" disabled>Cancel</button>
        <button type="submit"
          class="text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-[var(--primary-dark)] font-medium rounded-lg text-lg py-2 w-1/2 h-11">Place Order</button>
      </div>
    </div>
  </div>

  <div id="popup-modal" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <button type="button" class="cancel absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to remove this product?</h3>
          <button data-modal-hide="popup-modal" type="button" class="delete-btn text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
            Yes, I'm sure
          </button>
          <button data-modal-hide="popup-modal" type="button" class="cancel py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div id="cancel-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-[400px] flex flex-col items-center">
    <svg class="w-12 h-12 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
</svg>

        <h2 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to cancel?</h2>
        <div class="flex justify-between gap-4">
            <button id="confirm-cancel" class="delete-btn text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                Yes, cancel order
            </button>
            <button id="deny-cancel" class="  py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                No, keep order
            </button>
        </div>
    </div>
</div>



  @endsection