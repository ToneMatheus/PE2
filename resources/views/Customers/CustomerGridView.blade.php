

<x-app-layout title="Gridview">
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Customer list') }}
    </h2>
</x-slot>


<!-- <div class="container">
    <div class="row">
        <form id="searchForm">
            <input type="text" name="search" id="searchInput" required value="{{ request('search') }}">
            <button type="submit">Search</button>
        </form>
        <a href="{{ route('customerGridView') }}" class="reset-button">Clear</a>
    </div>
    <table id="customerTable">
        <thead>
            <th><a href="#" class="sort" data-column="id">ID</a></th>
            <th><a href="#" class="sort" data-column="last_name">Last Name</a></th>
            <th><a href="#" class="sort" data-column="first_name">First Name</a></th>
            <th><a href="#" class="sort" data-column="phone_nbr">Phone Number</a></th>
            <th><a href="#" class="sort" data-column="company_name">Company Name</a></th>
            <th><a href="#" class="sort" data-column="is_company">Is Company</a></th>
            <th><a href="#" class="sort" data-column="email">Email</a></th>
            <th><a href="#" class="sort" data-column="birth_date">Birth Date</a></th>
            <th><a href="#" class="sort" data-column="is_active">Is Active</a></th>
            <th><a href="#" class="sort" data-column="start_date">Start Date</a></th>
            <th><a href="#" class="sort" data-column="end_date">End Date</a></th>
            <th><a href="#" class="sort" data-column="type">Type</a></th>
            <th><a href="#" class="sort" data-column="price">Price</a></th>
            <th><a href="#" class="sort" data-column="status">Status</a></th>
            <th>Edit</th>
            <th>Add Invoice Line</th>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->last_name }}</td>
                    <td>{{ $customer->first_name }}</td>
                    <td>{{ $customer->phone_nbr }}</td>
                    <td>{{ $customer->company_name }}</td>
                    <td>{{ $customer->is_company }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->birth_date }}</td>
                    <td>{{ $customer->is_active }}</td>
                    <td>{{ $customer->start_date }}</td>
                    <td>{{ $customer->end_date }}</td>
                    <td>{{ $customer->type }}</td>
                    <td>{{ $customer->price }}</td>
                    <td>{{ $customer->status }}</td>
                    <td><a href="{{ route('customer.edit', ['id' => $customer->id]) }}">Edit</a></td>
                    <td><a href="{{ route('addInvoiceExtraForm', ['id' => $customer->id]) }}">Add Invoice Line</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $customers->links() }}
</div> -->

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div>
                <form id="searchForm" class="flex items-center space-x-2 mb-4">
                    <input type="text" name="search" id="searchInput" class="block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:border-indigo-300 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-300" required value="{{ request('search') }}">
                    <button type="submit" class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600">Search</button>
                    <a href="{{ route('customerGridView') }}" class="px-4 py-2 bg-gray-600 dark:bg-gray-500 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">Clear</a>
                </form>
                <div class="overflow-x-auto">
                    <table id="customerTable" class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="id">ID</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="last_name">Last Name</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="first_name">First Name</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="phone_nbr">Phone Number</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="company_name">Company Name</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="is_company">Is Company</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="email">Email</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="birth_date">Birth Date</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="is_active">Is Active</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="start_date">Start Date</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="end_date">End Date</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="type">Type</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="price">Price</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><a href="#" class="sort" data-column="status">Status</a></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Edit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Add Invoice Line</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($customers as $customer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->first_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->phone_nbr }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->company_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->is_company }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->birth_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->is_active }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->start_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->end_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->price }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $customer->status }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('customer.edit', ['id' => $customer->id]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500">Edit</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('addInvoiceExtraForm', ['id' => $customer->id]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500">Add Invoice Line</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>




    <script>
        $(document).ready(function() {
            $('.sort').click(function(e) {
                e.preventDefault();
                var column = $(this).data('column');
                var direction = 'asc';
                if ($(this).hasClass('sorted') && $(this).hasClass('asc')) {
                    direction = 'desc';
                }
                $('.sort').removeClass('sorted asc desc');
                $(this).addClass('sorted ' + direction);
                $.ajax({
                    url: '{{ route("customerGridView") }}',
                    type: 'GET',
                    data: {
                        search: $('#searchInput').val(),
                        sort: column,
                        direction: direction
                    },
                    success: function(response) {
                        $('#customerTable tbody').html($(response).find('#customerTable tbody').html());
                    }
                });
            });

            $('#searchForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("customerGridView") }}',
                    type: 'GET',
                    data: {
                        search: $('#searchInput').val()
                    },
                    success: function(response) {
                        $('#customerTable tbody').html($(response).find('#customerTable tbody').html());
                    }
                });
            });
        });
    </script>



    </x-app-layout>