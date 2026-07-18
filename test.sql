SELECT
            datgen.nombre, datgen.paterno, datgen.materno, datgen.email, datgen.telefono, datgen.cedula, datgen.sexo_id, datgen.domicilio_id, datgen.pais_id,
            datgen.escuela, esp.especialidad, datgen.titulo,
            usr.usuario as usuario_register, rol.name as rol,
            us.id, us.usuario, datgen.tipo_usuario_id, umed.fecha_limite_prueba, us.updated_at as usuario_updated_at,
            us.origen_id, us.theme, umusr.activo,
            sex.sexo, umed.nombre_unidad, umed.tipo_licencia_id, umed.estatus_licencia_id, umed.domicilio_id as unidad_medica_domicilio_id,
            umed.email_contacto_unidadmedica, umed.telefono_unidadmedica, umed.logo,
            umusr.titular, umusr.rol_id, umusr.unidad_medica_id,
            tipo_lic.tipo, tipo_lic.front_end
            FROM usuarios us
            INNER JOIN usuarios usr ON (usr.id = us.usuario_id_updated)
            INNER JOIN usuarios_datos_generales datgen ON (datgen.usuario_id = us.id)
            INNER JOIN unidades_medicas umed ON (umed.id = 1)
            INNER JOIN unidad_medica_usuarios umusr ON (umusr.usuario_id = us.id and umusr.unidad_medica_id = 1)
            INNER JOIN roles rol ON (rol.id = umusr.rol_id)
            INNER JOIN sexo sex ON (sex.id = datgen.sexo_id)
            LEFT JOIN especialidades esp ON (esp.id = datgen.especialidad_id)
            INNER JOIN tipo_licencia tipo_lic ON (tipo_lic.id = umed.tipo_licencia_id)
            WHERE
            datgen.residente_id = 742;