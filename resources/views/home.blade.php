@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Auth::user()->role == 'Administrator')
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header border-0 bg-transparent mb-2 fs-2 text-primary lead">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Informações de Usuário') }}</span>
                                <div class="col-5">
                                    <input type="search" id="searchUsers"
                                        class="form-control shadow-none rounded-0"
                                        placeholder="Digite aqui o que deseja pesquisar">
                                </div>
                                <div class="col-3">
                                    <button type="button"
                                        class="excelUsers btn btn-primary rounded-0 shadow-none">
                                        <span class="me-2"><i class=" fa-solid fa-file-excel"></i></span>
                                        {{ __('Exportar Excel ') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12 table-responsive" id="collegesTable">
                                <table class="table table-hover" id="UsersTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="row">#</th>
                                            <th scope="row">Nome</th>
                                            <th scope="row">E-mail</th>
                                            <th scope="row">Cargo</th>
                                            <th scope="row">Criado em:</th>
                                            <th scope="row">Última utilização em:</th>
                                            <th scope="row" class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $count = 1;
                                        @endphp
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $count++ }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->role }}</td>
                                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                                <td>{{ $user->updated_at->diffForHumans() }}</td>
                                                <td  class="text-center">
                                                    <a href="{{ route('users.destroy', ['id' => $user->id]) }}"
                                                        class="btn btn-outline-danger shadow-none rounded-circle">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', ['id' => $user->id]) }}"
                                                        class="btn btn-outline-info shadow-none rounded-circle">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (Auth::user()->role == 'Teacher')
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header border-0 bg-transparent mb-2 fs-2 text-primary lead">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Tabela de Análise de Estudantes') }}</span>
                                <div class="col-5">
                                    <input type="search" id="searchExamsStudent"
                                        class="form-control shadow-none rounded-0"
                                        placeholder="Digite aqui o que deseja pesquisar">
                                </div>
                                <div class="col-3">
                                    <button type="button"
                                        class="excelExamsStudent btn btn-primary rounded-0 shadow-none">
                                        <span class="me-2"><i class=" fa-solid fa-file-excel"></i></span>
                                        {{ __('Exportar Excel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12 table-responsive" id="collegesTable">
                                <table class="table table-hover" id="ExamsStudentTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="row">#</th>
                                            <th scope="row">Nome do Estudante</th>
                                            <th scope="row">Nome do Curso</th>
                                            <th scope="row">Sala</th>
                                            <th scope="row">Carga Horária</th>
                                            <th scope="row">Dia</th>
                                            <th scope="row">Horário de Início</th>
                                            <th scope="row">Horário de Termíno</th>
                                            <th scope="row">Status</th>
                                            {{-- <th scope="row" class="text-center">Ações</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>
@endsection


@section('scripts')
    @if (session()->has('success'))
        <script>
            toastr.success('{{ session()->get('success') }}');
        </script>
    @endif


    <script>
        $(document).ready(function() {
            $("#searchUsers").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#UsersTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $("#searchExamsStudent").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#ExamsStudentTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });


            $(document).on('click', '.excelExamsStudent', function(e) {
                e.preventDefault(e);
                const start = Date.now();
                TableToExcel.convert(document.getElementById("ExamsStudentTable"), {
                    name: `${start}_Exams Student.xlsx`,
                    sheet: {
                        name: "Sheet 1"
                    }
                });

            });
            $(document).on('click', '.excelUsers', function(e) {
                e.preventDefault(e);
                const start = Date.now();
                TableToExcel.convert(document.getElementById("UsersTable"), {
                    name: `${start}_Users.xlsx`,
                    sheet: {
                        name: "Sheet 1"
                    }
                });
                Users
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            fetch();

            function isPresenCheckt(isPresent) {

                if (isPresent == 1) {
                    return `<span class="badge bg-success">Presente</span>`;
                } else {
                    return `<span class="badge bg-danger">Ausente</span>`;
                }
            }



            function fetch() {
                $.ajax({
                    type: "GET",
                    url: "/teacher/studentExam/getStudentExam",
                    dataType: "json",
                    success: function(response) {
                        // console.log(response.student_exams);
                        var count = 1;
                        $('tbody').empty();
                        $.each(response.student_exams, function(key, item) {
                            $('tbody').append(
                                '<tr class="align-middle"><td>' + count++ + '</td><td>' +
                                item.studentName +
                                '</td><td>' +
                                item.name +
                                '</td><td>' +
                                item.nameHall +
                                '</td><td>' +
                                item.hours +
                                '</td><td>' +
                                item.day +
                                '</td><td>' +
                                item.start +
                                '</td><td>' +
                                item.end +
                                '</td><td>' + isPresenCheckt(item.isPresent) +
                                '</td></tr>'
                            );

                        });
                    }
                });
            }
        });
    </script>
@endsection
