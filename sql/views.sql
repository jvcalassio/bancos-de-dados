CREATE VIEW `proj_final`.`vw_secao_itens` AS
    SELECT 
        `itens`.`titulo` AS `titulo`,
        `itens`.`tipo` AS `tipo`,
        `itens`.`secao_id` AS `secao_id`,
        `proj_final`.`secao`.`curso_id` AS `curso_id`
    FROM
        ((SELECT 
            `proj_final`.`teste`.`titulo` AS `titulo`,
                'Teste' AS `tipo`,
                `proj_final`.`teste`.`secao_id` AS `secao_id`
        FROM
            `proj_final`.`teste` UNION ALL SELECT 
            `proj_final`.`trabalho`.`titulo` AS `titulo`,
                'Trabalho' AS `tipo`,
                `proj_final`.`trabalho`.`secao_id` AS `secao_id`
        FROM
            `proj_final`.`trabalho` UNION ALL SELECT 
            `proj_final`.`aula`.`titulo` AS `titulo`,
                'Aula' AS `tipo`,
                `proj_final`.`aula`.`secao_id` AS `secao_id`
        FROM
            `proj_final`.`aula` UNION ALL SELECT 
            `proj_final`.`material_apoio`.`titulo` AS `titulo`,
                'Material de apoio' AS `tipo`,
                `proj_final`.`material_apoio`.`secao_id` AS `secao_id`
        FROM
            `proj_final`.`material_apoio`) `itens`
        JOIN `proj_final`.`secao`)
    WHERE
        `proj_final`.`secao`.`id` = `itens`.`secao_id`;

CREATE VIEW `proj_final`.`vw_estatisticas_cursos` AS
    SELECT 
        `c`.`id` AS `id`,
        `c`.`titulo` AS `titulo`,
        COUNT(`s`.`id`) AS `qtd_secoes`,
        (SELECT 
                COUNT(0)
            FROM
                `proj_final`.`vw_secao_itens`
            WHERE
                `vw_secao_itens`.`curso_id` = `c`.`id`) AS `qtd_itens`,
        (SELECT 
                AVG(`qtd_itens`.`qtd`)
            FROM
                (SELECT 
                    COUNT(`vw_secao_itens`.`secao_id`) AS `qtd`,
                        `vw_secao_itens`.`curso_id` AS `curso_id`
                FROM
                    `proj_final`.`vw_secao_itens`
                GROUP BY `vw_secao_itens`.`secao_id` , `vw_secao_itens`.`curso_id`) `qtd_itens`
            WHERE
                `qtd_itens`.`curso_id` = `c`.`id`
            GROUP BY `qtd_itens`.`curso_id`) AS `media_itens_por_secao`,
        (SELECT 
                COUNT(0)
            FROM
                `proj_final`.`curso_usuario`
            WHERE
                `proj_final`.`curso_usuario`.`curso_id` = `c`.`id`) AS `qtd_alunos_inscritos`
    FROM
        (`proj_final`.`curso` `c`
        LEFT JOIN `proj_final`.`secao` `s` ON (`s`.`curso_id` = `c`.`id`))
    GROUP BY `c`.`id`