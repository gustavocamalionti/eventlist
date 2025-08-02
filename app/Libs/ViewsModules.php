<?php

/**
 * Created by PhpStorm.
 * User: Marcelo
 * Date: 08/05/17
 * Time: 21:48
 */

namespace App\Libs;

class ViewsModules
{
    const AUTH_LOGIN = "Login";
    const AUTH_REGISTER = "Registrar";
    const AUTH_VERIFY = "Verificar";
    const AUTH_CONFIRM = "Confirmar";
    const SITE_HOME = "Home";
    const SITE_STORES = "Lojas";
    const SITE_FRANCHISEE = "Seja Franqueado";
    const POLITICA_PRIVACIDADE = "Política de Privacidade";
    const SITE_CONTACT = "Contato";
    const SITE_FIDELITY = "Fidelidade";
    const SITE_FOOD_MENU = "Cardápio";
    const SITE_VOUCHERS = "Home | Vouchers";
    const SITE_HANDLE_PROCESSING_BUY = "Modal de processamento de compra";
    const WEBHOOK = "Rota de Webhook";
    const CRON_PAYMENT = "Cron que verifica status de compra";
    const REDIRECT_URL_PAYMENT_SUCCESS = "Rota de Redirecionamento de sucesso após pagamento";
    const CLIENT_HOME = "Home";
    const CLIENT_SUBSCRIPTION_BUY = "Compra de Plano";

    const FORM_CONTACT = "Formulário - Fale Conosco";
    const FORM_FRANCHISEE = "Formulário - Seja um Franqueado";
    const FORM_CONFIG = "Formulário - Configurações";

    const EVENT_BUY = "Evento - Vendas";
    const EVENT_VOUCHER = "Evento - Ingressos";

    const PANEL_DASHBOARD = "Dashboard";
    const PANEL_USERS = "Usuários";
    const PANEL_BANNERS = "Banners";
    const PANEL_STORES = "Lojas";
    const PANEL_LINKS = "Links";
    const PANEL_MENU_CATEGORIES = "Cardápio - Categorias";
    const PANEL_MENU_SUBCATEGORIES = "Cardápio - Subcategorias";
    const PANEL_MENU_PRODUCTS = "Cardápio - Produtos";
    const PANEL_PROFILE = "Perfil";
    const PANEL_LOG_AUDIT = "Log - Auditoria";
    const PANEL_LOG_ERRORS = "Log - Erros";
    const PANEL_LOG_EMAILS = "Log - Emails";
    const PANEL_LOG_WEBHOOKS = "Log - Webhooks";

    const PANEL_CONFIG_PARAMETERS = "Configurações - Parâmetros";
    const PANEL_CONFIG_COLORS = "Configurações - Cores";
    const PANEL_CONFIG_CONTENTS = "Configurações - Conteúdos";
}
