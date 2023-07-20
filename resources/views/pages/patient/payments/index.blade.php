@extends('layouts.patient')

@section('content')
    <x-utils.loading-component/>

    <section class="mt-10">
        <h3 class="my-2 font-bold text-lg">Payment List</h3>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label>Show
                <select class="bg-white p-1 border ml-2" name="select-show" id="select-show">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </label>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label class="w-full lg:w-2/3">
                Search
                <input
                    class="w-full"
                    type="search"
                    placeholder="Status, type"
                    id="search_field"
                    name="search_field"
                >
            </label>
            <label class="w-full lg:w-1/3">
                Date
                <input class="w-full" type="date">
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                <tr class="bg-secondary">
                    <th class="px-4 py-1">Total</th>
                    <th class="px-4 py-1">Método de Pago</th>
                    <th class="px-4 py-1">Estatus</th>
                    <th class="px-4 py-1">Fecha</th>
                    <th class="px-4 py-1">Acción</th>
                </tr>
                <tbody id="tbody"></tbody>
            </table>
        </div>

        <x-utils.pagination-component/>
    </section>

    <x-modal.modal-component
        title="Payment details"
        modalClass="modal"
    >
        <x-slot name="content">
            <div id="payment-detail" class="p-4"></div>
        </x-slot>
    </x-modal.modal-component>
@endsection
@push('scripts-bottom')
    <script>
        const selectShow = $('#select-show');
        let limit = selectShow.val();
        let search = '';

        searchData();

        // limit show with ajax
        selectShow.on('change', function () {
            limit = $(this).val()
            searchData();
        })

        // search user with ajax when after 5 seconds
        let timeout = null;
        $('#search_field').on('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                search = $('#search_field').val()
                searchData();
            }, 500);
        })

        // search data
        function searchData(page = 1) {
            showLoading()
            let url = `/patient/payments/search?search=${search}&limit=${limit}&page=${page}`
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: token
                },
                success: function (response) {
                    setDataTable(response)
                    hideLoading()
                },
                error: function () {
                    hideLoading()
                }
            })
        }

        function setDataTable(response) {
            let html = ''

            setPagination(response)

            response.data.data.forEach(function (item) {
                html += `<tr class="bg-white rounded">
                            <td class="px-4 py-2 text-center">${formatNumber(item.amount)}</td>
                            <td class="px-4 py-2 text-center capitalize">${item.payment_method}</td>
                            <td class="px-4 py-2 text-center capitalize">${item.payment_status}</td>
                            <td class="px-4 py-2 text-center">${formatDate(item.payment_date)}</td>
                            <td class="px-4 py-2 text-center">
                                <button
                                    id="modal-open-${item.id}"
                                    onclick='setDataModal(${JSON.stringify(item)})'
                                    class="rounded text-green-900 bg-green-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                                >
                                    Detalles
                                </but>
                            </td>
                        </tr>`
            })

            $('#tbody').html(html)

            response.data.data.forEach(function (item) {
                configModal('modal', `modal-open-${item.id}`)
            })
        }

        function setDataModal(item) {
            $('#payment-detail').html(`
                <ul class="space-y-2">
                    <li class="border-b-2"><strong>ID: </strong> ${item.id}</li>
                    <li><strong>Total: </strong> ${item.amount}</li>
                    <li><strong>Método de Pago: </strong> ${item.payment_method}</li>
                    <li><strong>Estatus: </strong> ${item.payment_status}</li>
                    <li><strong>Fecha: </strong> ${formatDate(item.payment_date)}</li>
                </ul>
            `)
        }

    </script>
@endpush
