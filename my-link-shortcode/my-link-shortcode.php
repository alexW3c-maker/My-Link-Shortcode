<?php
/*
Plugin Name: My Link Shortcode
Description: Создает шорткод для генерации ссылок разных типов.
Version: 1.0
Author: alexW3c_maker
*/

if (!defined('ABSPATH')) {
    exit; // Защита от прямого доступа к файлу
}

function link_to( $params ) {
    $type = isset( $params['type'] ) ? $params['type'] : 'default';
    $href = isset( $params['href'] ) ? $params['href'] : '';
    $content = isset( $params['content'] ) ? $params['content'] : '';

    switch ( $type ) {
        case 'email':
            $href = 'mailto:' . $href;
            break;
        case 'phone':
            $href = 'tel:' . preg_replace( '/\D/', '', $href );
            break;
    }

    return sprintf( '<a href="%s">%s</a>', esc_url( $href ), esc_html( $content ) );
}

function my_link_shortcode( $atts ) {
    $params = shortcode_atts( array(
        'type' => 'default',
        'href' => '',
        'content' => '',
    ), $atts );


    if ( empty( $params['type'] ) ) {
        $params['type'] = 'default';
    }

    if ( empty( $params['content'] ) ) {
        $params['content'] = $params['href'];
    }

    return link_to( $params );
}

add_shortcode( 'my_link', 'my_link_shortcode' );

function my_link_shortcode_menu() {
    add_options_page(
        'My Link Shortcode',
        'My Link Shortcode',
        'manage_options',
        'my-link-shortcode',
        'my_link_shortcode_options_page'
    );
}

add_action( 'admin_menu', 'my_link_shortcode_menu' );

function my_link_shortcode_options_page() {
    ?>
    <div class="wrap">
        <h1>My Link Shortcode</h1>
        <p>Используйте следующий шорткод для добавления ссылки на своем сайте:</p>
        <pre><code>[my_link type="default|email|phone" href="URL" content="Текст ссылки"]</code></pre>
        <p>Описание параметров:</p>
        <ul>
            <li><code>type</code>: тип ссылки (default, email, phone). Если не указан, используется значение 'default'.</li>
            <li><code>href</code>: URL ссылки. Для типов 'email' и 'phone' значение будет преобразовано соответственно.</li>
            <li><code>content</code>: текст ссылки. Если не указан, используется значение 'href'.</li>
        </ul>
    </div>
    <?php
}