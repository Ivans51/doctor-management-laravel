@extends('layouts.admin')

@section('content')
    <x-utils.loading-component/>

    <section>
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Payment list</h3>
        </div>

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
            <label class="w-full">
                Search
                <input class="w-full" type="search" id="search_field" placeholder="Name, email">
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                <thead>
                <tr class="bg-zinc-100">
                    <th class="px-4 py-1 text-left">ID</th>
                    <th class="px-4 py-1">Total</th>
                    <th class="px-4 py-1">Método de Pago</th>
                    <th class="px-4 py-1">Estatus</th>
                    <th class="px-4 py-1">Fecha</th>
                    <th class="px-4 py-1">Acción</th>
                </tr>
                </thead>
                <tbody id="tbody"></tbody>
            </table>
        </div>
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

        getData();

        // limit show with ajax
        selectShow.on('change', function () {
            limit = $(this).val()
            getData();
        })

        function getData() {
            const doctorId = '{{ $doctorId }}'
            const query = doctorId ? `?doctorId=${doctorId}` : ''

            let url = `/admin/payments/index/limit${query}`
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    limit: limit,
                    _token: token
                },
                success: function (response) {
                    setDataTable(response);
                }
            })
        }

        // search user with ajax
        $('#search_field').on('keyup', function () {
            const doctorId = '{{ $doctorId }}'
            const query = doctorId ? `?doctorId=${doctorId}` : ''

            let search = $(this).val()
            let url = `/admin/payments/search${query}`
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    search: search,
                    _token: token
                },
                success: function (response) {
                    setDataTable(response);
                }
            })
        })

        function setDataTable(response) {
            let html = ''

            response.data.data.forEach(function (item) {
                html += `<tr class="bg-white rounded">
                            <td class="px-4 py-2 text-center">${item.id}</td>
                            <td class="px-4 py-2 text-center">${item.amount}</td>
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

        // format date dd/mm/yyyy
        function formatDate(date) {
            let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear()

            if (month.length < 2)
                month = '0' + month
            if (day.length < 2)
                day = '0' + day

            return [day, month, year].join('/')
        }


    </script>
@endpush
