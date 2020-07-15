<template>
    <div class="row">
        <form class="col s12 mt-3">
            <div class="row">
                <div class="input-field col s12">
                    <textarea class="materialize-textarea validate" data-length="280"
                              v-model="problema"

                              :class="{'invalid': error_problema !== ''}"

                              @focus="error_problema = ''"></textarea>
                    <label>Problema</label>
                    <span class="helper-text"
                        v-show="error_problema !== ''"
                        :data-error="error_problema"></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light" type="button"
                        :disabled="resolver"

                        @click="resolverProblema">
                        Resolver <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
            <div class="row">

                <div class="col s12 mt-3"
                    v-show="Object.keys(resultado).length > 0">

                    <div class="row">
                        <div class="col s12 m6">
                            <h3>Se solicita</h3>
                            <ul>
                                <li
                                    v-for="se_solicita in resultado.se_solicita"

                                    v-html="se_solicita">

                                </li>
                            </ul>

                            <h3>Resultado</h3>
                            <ul>
                                <li
                                    v-for="(valorResultado, nombreResultado) in resultado.resultado"

                                    v-html="nombreResultado + ': ' + valorResultado">

                                </li>
                            </ul>
                        </div>

                        <div class="col s12 m6">
                            <h3>Datos</h3>
                            <ul>
                                <li
                                    v-for="dato in resultado.datos">

                                    <strong v-html="dato.dato + ': '"></strong>
                                    <span v-html="dato.valor + ' ' + dato.unidad"></span>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                problema: "",
                error_problema: "",

                resolver: false,

                resultado: {},
            }
        },

        methods: {

            resolverProblema () {

                this.resultado = [];
                this.resolver = true;

                if(this.problema !== '') {

                    const dataResolverProblema = {
                        problema: this.problema,
                    };

                    axios
                        .post('/api/resolver-problema', dataResolverProblema)
                        .then( (responseResolverProblema) => {

                            const response = responseResolverProblema.data;

                            if(response.error) {
                                this.error_problema = response.mensaje;
                            } else {
                                this.resultado = response.problema;
                            }

                            this.resolver = false;


                        }).catch(responseError => {

                            this.resolver = false;

                            let response = responseError.response.data;

                            let mensaje = 'Error al obtener la resolución del problema ';

                            if (response.errors) {
                                mensaje = '<ul>';

                                Object.values(response.errors).forEach((error, iError) => {
                                    mensaje += '<li>' + error + '</li>';
                                });

                                mensaje += '</ul>';
                            }
                    });

                } else {
                    this.resolver = false;
                    this.error_problema = 'Tiene que ingresar el problema';
                }
            }
        }
    }
</script>

<style scoped>

    .mt-1 {
        margin-top: 0.5rem;
    }

    .mt-2 {
        margin-top: 1rem;
    }

    .mt-3 {
        margin-top: 1.5rem;
    }


</style>
