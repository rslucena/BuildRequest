<?php

class pages{

    use jsonCovert;

    public function getPsicoClique( $parans = null ) {

        $acess = empty($parans) ? 0 : $_GET['acess'];

        $results = array( 'query' => true);

        /** BANNER */
        $results['vals'] = array( 'banner' => array(
            'logo' => "/images/psicoclique/psicoclique.png" ,
            'avatar' => '/images/psicoclique/banner-psicoclique.jpg',
            'title' => 'A plataforma online oficial da academia do psicólogo',
            'content' => 'A psicoclique é a "plataforma de atendimento online oficial" da Academia do Psicólogo, com ela você tem acesso a um ambiente de atendimento seguro e funcional totalmente alinhado com as melhores práticas propostas pelo Conselho Federal de Psicologia.',
            'alert' => 'A proposta é tornar a psicologia mais acessível para as pessoas em todo o Brasil, atendendo aqueles que não podem se locomover até um consultório ou preferem fazer o atendimento online de casa, do trabalho, ou de qualquer lugar do mundo.'
        ));

        /** HOW FUNCTION */
        $results['vals'] += array( 'howfunction' => array(
            'title' => 'Como funciona',
            'content' => 'Para ter acesso à psicoclique como psicólogo é muito simples, basta você se tornar o associado Premium do psico.club que você passa automaticamente a ter acesso a todas as funcionalidades do plano Standard.',
        ));

        /** WHAT YOUR GET*/
        $results['vals']['whatget'] = array(
            array(
                'avatar' => '/images/icone-perfilpsicoclique.png',
                'title' => 'Perfil profissional',
                'content' => 'Alcance mais visibilidade em uma plataforma que faz parte da Academia do Psicólogo e é referência em psicoterapia online.'
            ),
            array(
                'avatar' => '/images/icone-calendario-c.png',
                'title' => 'Agenda 24h',
                'content' => 'Alcance mais visibilidade em uma plataforma que faz parte da Academia do Psicólogo e é referência em psicoterapia online.',
            ),
            array(
                'avatar' => '/images/icone-conteudo-seguro.png',
                'title' => 'Sessão segura',
                'content' => 'Atenda seus clientes de qualquer lugar do mundo usando o nosso protocolo de segurança.',
            ),
            array(
                'avatar' => '/images/icone-cursos.png',
                'title' => 'Material de apoio',
                'content' => 'Organize suas sessões em um só lugar. Temos modelos de anamneses, atestados e declarações.',
            ),
            array(
                'avatar' => '/images/icone-alerta-psicoclique-email.png',
                'title' => 'Lembrete para os clientes',
                'content' => 'Seus clientes recebem lembretes antes da sessão, via e-mail para evitar desencontros ou esquecimentos.',
            ),
            array(
                'avatar' => '/images/icone-alerta-psicoclique.png',
                'title' => 'Alertas úteis',
                'content' => 'Seja lembrado sobre sessões, pagamentos e compromissos da sua agenda.',
            ),
            array(
                'avatar' => '/images/icone-pessoas-psicoclique.png',
                'title' => 'Acesso aos dados',
                'content' => 'Tenha acesso aos dados pessoais dos seus clientes podendo levá-los para o consultório presencial.',
            ),
            array(
                'avatar' => '/images/icone-feedbackpsicoclique.png',
                'title' => 'Depoimentos',
                'content' => 'Receba depoimentos dos seus clientes e apresente esses relatos na sua página para gerar mais credibilidade.',
            )
        );

        /** BTN */
        $results['vals']['btn'] = array(
            'title' => 'Assinar o psico.club',
            'link' => 'https://psico.club/cadastro/premium'
        );

        /** ACESS PLATFORM */
        $results['vals']['acess'] = array(
            'title' => 'Acessar a plataforma',
            'content' => 'Para ter acesso agora mesmo à psicoclique basta se tornar um associado Premium do psico.club e depois retornar a esta página que seu acesso estará liberado.<br /><br />Lembrando que sua assinatura do psico.club dá acesso também a diversas outras áreas do site, incluindo aulas, eventos especiais, modelos de contrato, ferramentas, clube de descontos e muito mais. Tudo isso por apenas 24,90 mensais.<br /><br />Para começar agora mesmo, é só clicar no link abaixo.',
            'avatar' => '/images/psicoclique/psicoclique_seo.png'
        );


        if($acess == 1){
            $results['vals']['acess']['content'] = 'Para acessar agora mesmo a plataforma é só clicar no botão abaixo. Seu acesso já está embutido no valor de sua assinatura do psico.club.';
            $results['vals']['btn'] = array(
                'title' => 'Acessar a plataforma online',
                'link' => 'http://www.psicoclique.com/psicoclub'
            );
        }


        echo $this->encodeJson($results);

    }

}
