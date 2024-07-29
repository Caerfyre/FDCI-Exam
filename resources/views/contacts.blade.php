@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="display-6 pb-2">Contacts</h1>
                <div class="col pb-3">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('contacts.add') }}" class="btn btn-primary">Add Contact</a>
                        <div class="form-group">
                            <input type="text" id="search" class="form-control" placeholder="Search contacts...">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body ">
                        @if ($contacts->isEmpty())
                            <p class="card-text text-center">You have no contacts.</p>
                        @else
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Company</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="contacts-table">
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            <td>{{ $contact->name }}</td>
                                            <td>{{ $contact->company }}</td>
                                            <td>{{ $contact->phone }}</td>
                                            <td>{{ $contact->email }}</td>
                                            <td>
                                                <a href="{{ route('contacts.edit', $contact->contactID) }}"
                                                    class="btn btn-success">Edit</a>

                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $contact->contactID }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        {{-- MODAL --}}
                                        <div class="modal fade" id="deleteModal{{ $contact->contactID }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Contact
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <p>Are you sure you want to <b>DELETE</b> {{ $contact->name }}?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">No</button>
                                                        <form action="{{ route('contacts.destroy', $contact->contactID) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Yes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- MODAL --}}
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="pagination" class="d-flex justify-content-center">
                                {{ $contacts->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var query = $(this).val();

                $.ajax({
                    url: '{{ route('contacts.search') }}',
                    type: 'GET',
                    data: {
                        'query': query
                    },
                    success: function(data) {
                        $('#contacts-table').html('');
                        $.each(data, function(index, contact) {
                            $('#contacts-table').append(
                                '<tr>' +
                                '<td>' + contact.name + '</td>' +
                                '<td>' + contact.company + '</td>' +
                                '<td>' + contact.phone + '</td>' +
                                '<td>' + contact.email + '</td>' +
                                '<td>' +
                                '<a href="/contacts/' + contact.contactID +
                                '/edit" class="btn btn-success me-1">Edit</a>' +
                                '<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' +
                                contact.contactID + '">Delete</button>' +
                                '</td>' +
                                '</tr>' +
                                '<div class="modal fade" id="deleteModal' + contact
                                .contactID +
                                '" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">' +
                                '<div class="modal-dialog">' +
                                '<div class="modal-content">' +
                                '<div class="modal-header">' +
                                '<h1 class="modal-title fs-5" id="deleteModalLabel">Delete Contact</h1>' +
                                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                '</div>' +
                                '<div class="modal-body">' +
                                '<p>Are you sure you want to DELETE ' + contact
                                .name + '?</p>' +
                                '</div>' +
                                '<div class="modal-footer">' +
                                '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>' +
                                '<form action="/contacts/' + contact.contactID +
                                '" method="POST">' +
                                '@csrf' +
                                '@method('DELETE')' +
                                '<button type="submit" class="btn btn-danger">Yes</button>' +
                                '</form>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>'
                            );
                        });
                    }
                });
            });
        });
    </script>

@endsection
