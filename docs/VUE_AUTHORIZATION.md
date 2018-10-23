# Directiva Vue a mida can (v-can)

La idea és poder fer conditional rendering (a l'estil v-show i v-if) però depenent dels permissos de l'usuari.

- NOTA: permisos, abilitats, acls. etc

Exemple:

Columna d'un datatable amb operacions Crud (a l'exemple només poso delete):

```html
<tr v-for="task i tasks">
 ...
 <td>
   <delete-task-button v-can:delete="task"></delete-task-icon>
  </td>
  ....
</tr>
```

Seria equivalent a:

```
<delete-task-button v-if="$can('delete', job)"></delete-task-icon>
```

De fet afegirem $can i $cannot al prototip de vue per poder utilitzar-los arreu

La idea és que només mostri el botó per esborrar si l'usuari té els permisos per a fer-ho.

Quan l'usuari sol tenir el permís? La idea és precisament que al front-end no li calgui saber-ho, encapsulem
el codi de comprovació en una policy/acl/abilitat o com li vulguem dir.

En la realitat sol haver dos casos que et donene permis a fer la operació
- La tasca és de l'usuari ( task.user_id === user.id)
- L'usuari té un permís (sovint assignat a través d'un Rol o Grup de permisos) que li permet realitzar l'operació:
  - permís: task.delete i per exemple tots els usuaris amb el Rol TaskManager poden realitzar l'operació delete sobre 
  qualsevol tasca
  
## Sintaxi de la directiva

v-can:verb="model"

- verb: l'operació que es vol realitzar: store | update | destroy | send | o el verb que vulguem
- model/recurs: pot ser opcional. Exemples opcional: store crea un nou model i per tant no te sentit passar. 
  En altres casos pel que sigui només mirarem si l'usuari té un rol/permis especific
  
## Objectiu

- Les acls no les definirem a Javascript sinó que les agafarem del backend. En el nostre cas Laravel Authorization 
amb Laravel Permission   
- Crear directiva v-can i v-cannot
- Dos maneres de no mostrar (com v-if i v-show)
  
#Exemples

## Codeburst/Medium Michael

- Blog: https://codeburst.io/reusable-vue-directives-v-can-753bf54e563f
- github repo: https://github.com/mblarsen/vue-browser-acl#flavors

## Directiva simple exemple a Laracast

-https://laracasts.com/discuss/channels/vue/roles-and-permissions-in-vue

# Javascript

## CASL

https://stalniy.github.io/casl/

#Recursos
- Directives a mida: https://vuejs.org/v2/guide/custom-directive.html
- https://codeburst.io/reusable-vue-directives-v-can-753bf54e563f
- https://github.com/mblarsen/vue-browser-acl#flavors
- https://pineco.de/implementing-laravels-authorization-front-end/
- https://laracasts.com/discuss/channels/vue/roles-and-permissions-in-vue

