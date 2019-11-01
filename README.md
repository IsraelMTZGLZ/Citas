1. Utilizar y crear el esquema creado de base de datos entregado junto con este manual (exam_sw11.sql) para crear la base de datos, se peuden crear vistas de ser necesario.

2. Crear un usuario para el uso de la base de datos con sus iniciales ejemplo si el nombre del Alumno es Martín López Pérez el usuario tendrá que ser mloperez y la contraseña para todos será .ordinario. -Se deberá incluir al archivo sql (exam_sw11.sql) los comandos para crear el usuario en formato sql.

3. Extraer el archivo julio_ordinario.zip en su carpeta htdocs o html según sea el sistema operativo y configurar los archivos necesarios para el funcionamiento del api, sólo se pueden agregar líneas no quitar, ni modificar.

4. Crear las funciones necesarias para registro y consulta(por expediente) de pacientes, en la carpeta pacientes.

5. Crear las funciones para el control de Citas en la carpeta citas:
  5.1. Obtener las citas generales y por médico.
  5.2. Obtener los médicos disponibles por especialidad (utilizar la vista ordinario_medico_especialidad_view y filtar de ahí).
  5.3. Registrar una cita [medico,paciente,pago?].
  5.4. Atender cita actualizar el campo citaAntencion,citaProceso.
  5.5. Cita crear receta [cita, nota].
  5.6. Terminar Cita actualizar el campo citaTermino,citaProceso.
  5.7. Listar recetas por paciente
(No se pueden quitar la funciones que ya están escritas en el api ** se deben usar todas las ya creadas y ahí insertar el código necesario, sin embargo si se pueden crear mas de ser necesario).

6. Crear el esquema web con las siguientes especificaciones:
  * Diseño libre(boostrap).
  * Registro de pacientes y citas.
7. Crear la app móvil que permita autenticar sólo médicos y pacientes.
  * Paciente: sólo puede ver sus recetas.
  * Médico: Puede ver sus citas, Atender, crear receta y terminar cita.

  ** El diseño de la app móvil estará en el pintarron el cual deberá seguirse.

  **No se resuelven dudas técnicas, sólo referentes a la redacción o de este archivo.

  Ponderación.

  10 La app esta al 100% incluyendo la subida de fotografía del paciente desde Web.

  9 La app esta al 100% pero sin la subida de fotografía.

  8.5 La app sólo carece de la vista del Paciente.

  8 La app carece del registro de la Receta.

  7.5 La app sólo no permite atender la Cita (muestra las citas, pero no puede atenderlas).

  7
  Opción 1 La web esta al 100% y en la app el login reconoce ambos tipos de usuarios y se generá la lista normal de citas sin diferenciar médico o

  Opción 2 La móvil realiza todo a excepción de recetas y la web sólo esta la vista con consulta de citas pero sin funcionalidad en registro.

  ** No puede haber código que haga referencia a variables de otra aplicación en caso de haber se descontará 15 décimas por cada variable irregular.
  ** La ponderaciones pueden variar dependiendo del diseño [sólo aplica para desacomodo de componentes].

  Archivos a entregar:

  * exam_sw11.sql actualizado y que se ejecute en una consola de mysql con sólo copiar y pegar, un error de sintaxis baja calificación.
  * julio_ordinario actualizado.
  * Aplicación Web.
  * www de la app móvil.

  Todos los archivos dentro de una carpeta con su nombre sin espacios que será comprimida en zip, rar o tar. sino viene comprimida baja 5 décimas de calificación.
