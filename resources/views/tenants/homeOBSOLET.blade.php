@extends('tenants.layouts.app')

@section('content')

    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-toolbar color="blue darken-3">
                    <v-toolbar-side-icon class="white--text"></v-toolbar-side-icon>
                    <v-toolbar-title class="white--text title">Dades professorat i places</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" >
                        <v-icon>settings</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-container fluid grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex xs3>
                            <bar :labels="{{ $teacherTotals }}"
                                 :data="{{ $teacherTotalsData }}"
                                 :colors ="{{ $teacherTotalsColors }}"
                                 title="Totals professors"></bar>
                        </v-flex>
                        <v-flex xs2>
                            <donut :labels="{{ $teacherTypes }}"
                                   :data="{{ $teacherTypesData }}"
                                   title="Tipus de places"></donut>
                        </v-flex>
                        <v-flex xs2>
{{--                            <donut :labels="{{ $tipusJornades }}" title="Places per tipus Jornada"></donut>--}}
                        </v-flex>
                        <v-flex xs6>
                            <donut :labels="{{ $specialties->keys() }}"
                                   :data="{{ $specialties->values() }}"
                                   title="Places per especialitats"></donut>
                        </v-flex>
                        <v-flex xs6>
                            <donut :labels="{{ $families->keys() }}"
                                   :data="{{ $families->values() }}"
                                   title="Places per famílies"></donut>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
            <v-flex xs12>
                <audit-log :entries="{{ $auditLogItems }}"></audit-log>
            </v-flex>
        </v-layout>
    </v-container>

    <v-flex xs12>
        <v-card>
            <v-card-text class="px-0 mb-2 hidden-sm-and-down">
                <h4>Removes</h4>
                <ul>
                    <li>S'ha de vigilar amb l'opció esborrar! Per exemple les places es poden esborrar si són errors clars i no etnen dades relacionades.
                        Per exemple una plaça amb un horari assignat no té sentit que es pugui esborrar (si no s'esborra també l'horari)
                    </li>
                    <li></li>
                </ul>
                <h4>TODO</h4>
                <ul>
                    <li>Fitxers adjunts: fotocopies del DNI</li>
                    <li>Foto proposada pel professorat: Alta professor -> Convertir a mides nostres? Requerir-les?</li>
                    <li>Taula leaves per baixes i permisos. Al afegir un substitut es crea baixa automàticament</li>
                    <li>Opció de marcar professor de baixa -> La plaça es posa com a pendent d'ocupar</li>
                    <li>Fer aplicació que no sigui obligatori gestionar baixes?</li>
                    <li>Botó settings: qui rep alertes/notificacions (emails) sobre canvir en professorat o places</li>
                    <li>Datatables de dades secundaries en 4 blocs: dades personals (emails, tlefons,adreces) | HABILITACIONS (especialitats professorat) | Dades formació | Altres dades</li>
                </ul>
                <h4>Places</h4>
                <ul>
                    <li>Número total de places (de tipus professorat - no incloure conserges ni altres)</li>
                    <li>Places per tipologia (Jornada sencera, mitja jornada, 1/3, etc). TODO: afegir camp ocupació (numero fins al 100)</li>
                    <li>Número total de places per ocupació (Per exemple 110 places pero 10 són mitja jornada-> 105)</li>
                    <li>Places no cobertes (places pendents assignar titular). Link cap a estes places per poder assignar. Alta nou professor es podria utilitzar esta info per facilitar l'alta nou profe?</li>
                    <li>Places per tipologies: especialitat, familia (gràfiques tipus formatgets)</li>
                    <li>Número total conserges, administratives</li>
                    <li>Links a la gestió de places</li>
                    <li>Link al llençol de professors</li>

                </ul>
                <h4>Profes</h4>
                <ul>
                    <li>TOP/MÉS IMPORTANT: Número profes pendents (podria ser fins i tot notificació/alerta per a cap estudis)</li>
                    <li>Número total profes: inclou tot tipus de profes han passat pel centre durant l'any</li>
                    <li>Número total profes actius: Hauria de coincidir amb nombre de places</li>
                    <li>Número total profes baixa o permis: TODO crear taula leaves (per guardar baixes i permisos)</li>
                    <li>Total profes per departaments/families/especialitats: gràfica tipus formatget???</li>
                    <li>Número total profes per tipus (Funcionaris (definitius, provisionals, en pràctiques), Interins, Substituts). Formatget</li>
                    <li>Profes sense plaça assignada (hauria de ser 0)</li>
                </ul>
                <h4>Profes. Estadístiques secundaries</h4>
                <ul>
                    <li>Alertes falta de dades: email personal, telèfons, adreça</li>
                    <li>ES podria crear datatable dades professors es pugues veure quines dades falte</li>
                    <li>Especialitat professors: habilitacions que tenen, qui no les té posades</li>
                    <li>DADES FORMACIó: Titulació accés, Altres titulacions, Idiomes, Perfils?</li>
                    <li>Altres dades: Data superació opos, anys inici serveis ensenyament (calcul antiguitat), any inici centre? destinació?</li>
                </ul>
            </v-card-text>
        </v-card>
    </v-flex>

@endsection


