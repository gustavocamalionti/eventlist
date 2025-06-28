@switch($key)
    @case("name")
        <strong>Nome:</strong>
        {{ $value }}
        <br />

        @break
    @case("description")
        <strong>Descrição:</strong>
        {{ $value }}
        <br />

        @break
    @case("date_start")
        <strong>Início do Agendamento:</strong>
        {{ $value != null || $value != "" ? date("d/m/Y H:i:s", strtotime($value)) : null }}
        <br />

        @break
    @case("date_end")
        <strong>Fim do Agendamento:</strong>
        {{ $value != null || $value != "" ? date("d/m/Y H:i:s", strtotime($value)) : null }}
        <br />

        @break
    @case("order")
        <strong>Ordenação:</strong>
        {{ $value }}
        <br />

        @break
    @case("forms_id")
    @case("form_subjects_id")
        <strong>Formulário:</strong>
        {{ App\Models\FormSubject::find($value)->form->name }} - {{ App\Models\FormSubject::find($value)->name }}
        <br />

        @break
    @case("is_schedule")
        <strong>Agendamento:</strong>
        {{ $value ? "Ativo" : "Inativo" }}
        <br />

        @break
    @case("image")
        <strong>Nome da Imagem:</strong>
        {{ $value }}
        <br />

        @break
    @case("image_desktop")
        <strong>Nome da Imagem/DESKTOP:</strong>
        {{ $value }}
        <br />

        @break
    @case("image_desktop")
        <strong>Nome da Imagem/DESKTOP:</strong>
        {{ $value }}
        <br />

        @break
    @case("link_type")
        <strong>Tipo do Link:</strong>
        {{ $value == App\Libs\Enums\EnumLinkType::REDIRECT ? "Redirecionamento" : "Arquivo" }}
        <br />

        @break
    @case("slug")
        <strong>{{ env("APP_URL") }}/</strong>
        {{ $value }}

        <br />

        @break
    @case("title")
        <strong>Título:</strong>
        {{ $value }}
        <br />

        @break
    @case("links_id")
        <strong>Link de Redirecionamento:</strong>
        <a href="{{ getLinksSlug($value) }}" target="_blank">{{ getLinksSlug($value) }}</a>
        <br />

        @break
    @case("cnpj")
        <strong>CNPJ:</strong>
        {{ App\Libs\Utils::MaskFields($value, "##.###.###/####-##") }}
        <br />

        @break
    @case("cpf")
        <strong>CPF:</strong>
        {{ App\Libs\Utils::MaskFields($value, "###.###.###-##") }}
        <br />

        @break
    @case("phone_cell")
        <strong>Celular:</strong>
        {{ App\Libs\Utils::MaskPhone($value) }}
        <br />

        @break
    @case("zipcode")
        <strong>CEP:</strong>
        {{ App\Libs\Utils::MaskFields($value, "#####-###") }}
        <br />

        @break
    @case("address")
        <strong>Endereço:</strong>
        {{ $value }}
        <br />

        @break
    @case("district")
        <strong>Bairro:</strong>
        {{ $value }}
        <br />

        @break
    @case("date_birth")
        <strong>Data de Aniversário:</strong>
        {{ date("d/m/Y", strtotime($value)) }}
        <br />

        @break
    @case("cities_id")
        <strong>Cidade / Estado:</strong>
        {{ $value != null ? App\Models\Common\Citie::find($value)->name : "" }} /
        {{ $value != null ? App\Models\Common\Citie::find($value)->states->initials : "" }}
        <br />

        @break
    @case("active")
        <strong>Status:</strong>
        {{ $value == true ? "Ativo" : "Inativo" }}
        <br />

        @break
    @case("email_verified_at")
        <strong>Data Verificação(Email):</strong>
        {{ $value != null ? date("d/m/Y H:i:s", strtotime($value)) : "Não Verificado" }}
        <br />

        @break
    @case("roles_id")
        <strong>Nível de Permissão:</strong>

        @switch($value)
            @case(App\Libs\Enums\Systems\Tenant\EnumTenantRoles::ADMIN)
                ADMINISTRADOR

                @break
            @case(App\Libs\Enums\Systems\Tenant\EnumTenantRoles::OWNER)
                PROPRIETÁRIO

                @break
            @case(App\Libs\Enums\Systems\Tenant\EnumTenantRoles::OPERATOR)
                OPERADOR

                @break
            @case(App\Libs\Enums\Systems\Tenant\EnumTenantRoles::PROMOTER)
                PROMOTER

                @break
            @default
        @endswitch
        <br />

        @break
    @case("news_accept")
        <strong>PERM. PARA ANUNCIOS:</strong>

        @switch($value)
            @case(App\Libs\Enums\EnumStatus::ACTIVE)
                SIM

                @break
            @case(App\Libs\Enums\EnumStatus::INACTIVE)
                NÃO

                @break
            @default
        @endswitch
        <br />

        @break
    @case("permission_accept")
        <strong>PERM. PARA DADOS:</strong>

        @switch($value)
            @case(App\Libs\Enums\EnumStatus::ACTIVE)
                SIM

                @break
            @case(App\Libs\Enums\EnumStatus::INACTIVE)
                NÃO

                @break
            @default
        @endswitch
        <br />

        @break
    @case("id")
        @break
    @case("details")
        @break
    @case("updated_at")
        @break
    @case("created_at")
        @break
    @case("password")
        @break
    @default
        <strong>{{ ucfirst($key) }}:</strong>
        {{ $value }}
        <br />
@endswitch
