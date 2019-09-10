@extends('layouts.layout', ['title' => __('Data Sources')])

@section('title')
    {{__('Edit Data Source')}}
@endsection

@section('sidebar')
    @include('layouts.sidebar', ['sidebar'=> Menu::get('sidebar_processes')])
@endsection

@section('breadcrumbs')
    @include('shared.breadcrumbs', ['routes' => [
       __('Designer') => route('processes.index'),
       __('Data Sources') => route('datasources.index'),
       __('Edit') . ' '  . $datasource['name'] => null,
   ]])
@endsection

@section('content')
    <div class="container" id="formDataSource">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-item nav-link active" id="nav-auth-tab" data-toggle="tab" href="#nav-auth"
                   role="tab" aria-controls="nav-auth" aria-selected="true">
                    {{ __('Authentication') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-item nav-link" id="nav-header-tab" data-toggle="tab" href="#nav-header"
                   role="tab" aria-controls="nav-header" aria-selected="true">
                    {{ __('End Points') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-item nav-link" id="nav-mapping-tab" data-toggle="tab" href="#nav-mapping"
                   role="tab" aria-controls="nav-header" aria-selected="true">
                    {{ __('Mappings') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-item nav-link" id="nav-test-tab" data-toggle="tab" href="#nav-test"
                   role="tab" aria-controls="nav-test" aria-selected="true">
                    {{ __('Test') }}
                </a>
            </li>
        </ul>

        <div class="container mt-3">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-auth" role="tabpanel" aria-labelledby="nav-auth-tab">
                    <div class="row">
                        <div class="card card-body">
                            <div class="form-group">
                                {!! Form::label('auth', __('Authentication Method')) !!}
                                <multiselect
                                        v-model="selectedAuthType"
                                        :options="authOptions"
                                        track-by="value"
                                        label="content"
                                        :allow-empty="false"
                                        :show-labels="false">
                                </multiselect>
                            </div>

                            <div class="form-group" v-show="formData.authtype === 'BEARER'">
                                {!! Form::label('token', __('Token')) !!}
                                {!! Form::textarea('token', null, ['id' => 'token', 'rows' => 4, 'class'=> 'form-control', 'v-model'=> 'credentials.token', 'rows' => 4, 'v-bind:class' => '{\'form-control\':true, \'is-invalid\':errors.token}']) !!}
                                <div class="invalid-feedback" v-if="errors.token">@{{errors.token[0]}}
                                </div>
                            </div>

                            <div class="form-group" v-show="formData.authtype === 'BASIC'">
                                {!! Form::label('user', __('User')) !!}
                                {!! Form::text('user', null, ['id' => 'user', 'class'=> 'form-control', 'v-model'=> 'credentials.user', 'v-bind:class' => '{\'form-control\':true, \'is-invalid\':errors.email}']) !!}
                                <div class="invalid-feedback" v-if="errors.user">@{{errors.user[0]}}
                                </div>
                            </div>

                            <div class="form-group" v-show="formData.authtype === 'BASIC'">
                                {!! Form::label('password', __('Password')) !!}
                                {!! Form::text('password', null, ['id' => 'password', 'class'=> 'form-control', 'v-model'=> 'credentials.password', 'v-bind:class' => '{\'form-control\':true, \'is-invalid\':errors.password}']) !!}
                                <div class="invalid-feedback" v-if="errors.password">@{{errors.password[0]}}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="nav-header" role="tabpanel" aria-labelledby="nav-header-tab">
                    <div class="row">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col">
                                </div>
                                <div class="col-8" align="right">
                                    <button type="button" href="#" @click="addEndpoint" id="add_endpoing"
                                            class="btn btn-secondary">
                                        <i class="fas fa-plus"></i> {{__('Add')}}
                                    </button>
                                </div>
                            </div>

                            <end-point-list
                                ref="endpointsListing"
                                :info="formData.endpoints">
                            </end-point-list>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="nav-mapping" role="tabpanel" aria-labelledby="nav-mapping-tab">
                    <div class="row">
                        <div class="card card-body">
                            <h5>{{ __('Mapping') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="nav-test" role="tabpanel" aria-labelledby="nav-test-tab">
                    <div class="row">
                        <div class="card card-body">
                            <h5>{{ __('Test') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-body">
            <div class="col text-right mt-2">
                {!! Form::button(__('Cancel'), ['class'=>'btn btn-outline-secondary', '@click' => 'onClose'])!!}
                @can('create-datasources')
                    {!! Form::button(__('Save'), ['class'=>'btn btn-secondary ml-2', '@click' => 'onSubmit'])!!}
                @endcan
            </div>
        </div>

        {{--<b-modal
            ref="modalParameter"
            :title="$t('Header')"
            :ok-title="$t('Save')"
            :cancel-title="$t('Cancel')"
            cancel-variant="outline-secondary"
            ok-variant="secondary"
            @ok="addParameter"
        >
            <div class="form-group">
                {!! Form::label('key', __('Key')) !!}
                {!! Form::text('key', null, ['id' => 'title','class'=> 'form-control', 'v-model' => 'parameter.key', 'v-bind:class' => '{"form-control":true, "is-invalid":errors.key}']) !!}
                <div class="invalid-feedback" v-for="key in errors.key">@{{key}}</div>
            </div>
            <div class="form-group">
                {!! Form::label('value', __('Value')) !!}
                {!! Form::textarea('value', null, ['id' => 'value', 'rows' => 4, 'class'=> 'form-control', 'v-model' => 'parameter.value', 'v-bind:class' => '{"form-control":true, "is-invalid":errors.value}']) !!}
                <div class="invalid-feedback" v-for="value in errors.value">@{{value}}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('description', __('Description')) !!}
                {!! Form::textarea('description', null, ['id' => 'description', 'rows' => 4, 'class'=> 'form-control', 'v-model' => 'parameter.description', 'v-bind:class' => '{"form-control":true, "is-invalid":errors.description}']) !!}
                <div class="invalid-feedback" v-for="description in errors.description">@{{description}}
                </div>
            </div>
        </b-modal>--}}

    </div>

@endsection

@section('js')
    <script src="{{mix('js/processes/datasources/edit.js')}}"></script>
    <script>
      const methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
      const authorizations = [
        {
          'value': 'NONE',
          'content': __('No Auth')
        },
        {
          'value': 'BASIC',
          'content': __('Basic auth')
        },
        {
          'value': 'BEARER',
          'content': __('Bearer Token')
        }
      ];
      new Vue({
        el: '#formDataSource',
        data() {
          return {
            selectedAuthType: '',
            methodOptions: methods,
            authOptions: authorizations,
            disabled: false,
            credentials: {
              token: '',
              user: '',
              password: ''
            },
            headers: [],
            errors: {},
            parameter: {
              key: '',
              value: '',
              description: '',
              type: ''
            },
            formData: @json($datasource)
          };
        },
        watch: {
          selectedAuthType: {
            handler(item) {
              if (item.value) {
                this.formData.authtype = item.value;
              }
            }
          },
          credentials: {
            handler(data) {
              this.formData.credentials = JSON.stringify(data);
            }
          },
          headers: {
            handler(data) {
              this.formData.credentials = JSON.stringify(data);
            }
          },
          formData: {
            immediately: true,
            deep: true,
            handler(data) {
              console.log('handler data');
              console.log(data);
            }
          }
        },
        computed: {},
        methods: {
          onClose() {
            console.log('close...');
          },
          getMethod() {
            return this.formData.id ? 'PUT' : 'POST';
          },
          getUrl() {
            return this.formData.id ? 'datasources/' + this.formData.id : 'datasources';
          },
          onSubmit() {
            console.log('save...');
            this.submitted = true;
            if (this.disabled) {
              return
            }
            this.disabled = true;
            ProcessMaker.apiClient({
              method: this.getMethod(),
              url: this.getUrl(),
              data: this.formData,
            })
              .then(function (response) {
                console.log('success');
                ProcessMaker.alert('{{__('The DataSource was saved.')}}', 'success');
                //window.location = '/designer/datasources';
              })
              .catch(error => {
                console.log('fail..');
                this.errors = error.response.data.errors;
                this.disabled = false;
              });
          },
          addEndpoint() {
            this.formData.endpoints = this.formData.endpoints ? this.formData.endpoints: [];
            let endpoint = {
              id: this.formData.endpoints.length > 0 ? this.formData.endpoints.length - 1: 0,
              view: false,
              method: '',
              url: '',
              header: [],
              body_type: '',
              body: ''
            };
            this.formData.endpoints.push(endpoint);
            this.$refs.endpointsListing.detail(endpoint);
          },
        },
        created() {
          console.log('created...');
          console.log(this.formData);
        },
        mounted() {
          console.log('mounted...');
          console.log(this.formData);
          this.selectedAuthType = this.authOptions.filter(item => {
            console.log(item);
            if (item.value === this.formData.authtype) {
              return item;
            }
          })
        },
      });
    </script>
@endsection