@extends('backend.layouts.app')
@section('title')
    {{ __('skill_list') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning">
                    This list will be displayed on the candidate settings page. The candidate can choose his skills from a
                    list.
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header ">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title line-height-36">{{ __('skill_list') }}
                                ({{ count($skills) }})</h3>
                            <div class=" d-inline-flex">
                                <button data-toggle="modal" data-target="#bulk_import_modal" class="btn bg-info"><i
                                        class="fas fa-plus mr-1"></i>
                                    {{ __('bulk_import') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>{{ __('name') }}</th>
                                    @if (userCan('skills.update') || userCan('skills.delete'))
                                        <th width="10%">{{ __('action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($skills as $skill)
                                    <tr>
                                        <td>
                                            <h5>{{ $skill->name }}</h5>
                                            <div>
                                                @foreach ($skill->translations as $translation)
                                                    @if (app()->getLocale() == $translation->locale)
                                                    @else
                                                        <span
                                                            class="d-block"><b>{{ getLanguageByCodeInLookUp($translation->locale,$app_language) }}</b>:
                                                            {{ $translation->name }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            @if (userCan('skills.update'))
                                                <a href="{{ route('skill.edit', $skill->id) }}" class="btn bg-info"><i
                                                        class="fas fa-edit"></i></a>
                                            @endif
                                            @if (userCan('skills.delete'))
                                                <form action="{{ route('skill.destroy', $skill->id) }}" method="POST"
                                                    class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button
                                                        onclick="return confirm('{{ __('are_you_sure_you_want_to_delete_this_item') }}');"
                                                        class="btn bg-danger"><i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            {{ __('no_data_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @if (!empty($skilll) && userCan('skills.update'))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title line-height-36">{{ __('edit') }} {{ __('skill') }}</h3>
                            <a href="{{ route('skill.index') }}"
                                class="btn bg-primary float-right d-flex align-items-center justify-content-center"><i
                                    class="fas fa-plus mr-1"></i>{{ __('create') }}
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="pt-3 pb-4">
                                <form class="form-horizontal" action="{{ route('skill.update', $skilll->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    @foreach ($app_language as $key => $language)
                                        @php
                                            $label = __('name') . ' ' . getLanguageByCode($language->code);
                                            $name = "name_{$language->code}";
                                            $code = $skilll->translations[$key]['locale'] ?? '';
                                            $data = $skilll->translations->where('locale', $language->code)->first();
                                            $value = $data ? $data->name : '';
                                        @endphp
                                        <div class="form-group">
                                            <x-forms.label :name="$label" for="name" :required="true" />
                                            <input id="name" type="text" name="{{ $name }}"
                                                placeholder="{{ __('name') }}" value="{{ $value }}"
                                                class="form-control @if ($errors->has($name)) is-invalid @endif">
                                            @if ($errors->has($name))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first($name) }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-plus mr-1"></i>
                                            {{ __('save') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                @if (empty($skilll) && userCan('skills.create'))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title line-height-36">{{ __('create') }} {{ __('skill') }}</h3>
                        </div>
                        <div class="card-body">
                            @if (userCan('job_role.create'))
                                <form class="form-horizontal" action="{{ route('skill.store') }}" method="POST">
                                    @csrf
                                    @foreach ($app_language as $key => $language)
                                        @php
                                            $label = __('name') . ' ' . getLanguageByCode($language->code);
                                            $name = "name_{$language->code}";
                                        @endphp
                                        <div class="form-group">
                                            <x-forms.label :name="$label" for="name" :required="true" />
                                            <input id="name" type="text" name="{{ $name }}"
                                                placeholder="{{ __('name') }}" value="{{ old('name') }}"
                                                class="form-control @if ($errors->has($name)) is-invalid @endif">
                                            @if ($errors->has($name))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first($name) }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-plus mr-1"></i>
                                            {{ __('save') }}
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p>{{ __('dont_have_permission') }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-3 d-flex overflow-auto justify-content-center">
                {{ $skills->links() }}
            </div>
        </div>
        <div class="modal fade" id="bulk_import_modal" tabindex="-1" role="dialog"
            aria-labelledby="bulk_import_modalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('bulk_import') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.skill.bulk.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-warning" role="alert">
                                Before importing, please download the example file and match the fields structure. If any
                                field data is missing, the system will generate it
                            </div>
                            <div class="form-group">
                                <label for="experience">{{ __('example_file') }}</label> <br>
                                <a href="/backend/dummy/skills_example.xlsx" target="_blank"
                                    class="btn btn-primary btn-block">
                                    <i class="fas fa-download"></i>
                                    {{ __('download') }} {{ __('example_file') }}
                                </a>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="experience">{{ __('choose_file') }}</label> <br>
                                <input type="file" class="form-control dropify" name="import_file"
                                    data-allowed-file-extensions='["csv", "xlsx","xls"]' accept=".csv,.xlsx,.xls"
                                    data-max-file-size="3M">
                                @error('import_file')
                                    <span class="invalid-feedback d-block" role="alert">{{ __($message) }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ __('close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
