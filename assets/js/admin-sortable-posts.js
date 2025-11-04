/**
 * 后台文章列表拖拽排序 JavaScript
 */

(function($) {
  'use strict';

  $(document).ready(function() {
    // 检查必要的变量
    if (typeof renaSortable === 'undefined') {
      return;
    }

    const $tbody = $('.wp-list-table tbody');
    
    if ($tbody.length === 0) {
      return;
    }

    // 创建保存消息元素
    const $message = $('<div class="rena-sort-message"></div>');
    $('.wp-list-table').before($message);

    // 初始化拖拽排序
    $tbody.sortable({
      handle: '.rena-sort-handle',
      items: 'tr:not(.no-items)',
      axis: 'y',
      cursor: 'move',
      opacity: 0.8,
      tolerance: 'pointer',
      placeholder: 'ui-sortable-placeholder',
      helper: function(e, tr) {
        const $originals = tr.children();
        const $helper = tr.clone();
        $helper.children().each(function(index) {
          $(this).width($originals.eq(index).width());
        });
        return $helper;
      },
      start: function(e, ui) {
        ui.placeholder.height(ui.item.height());
        $tbody.addClass('rena-sort-saving');
      },
      stop: function(e, ui) {
        $tbody.removeClass('rena-sort-saving');
        saveOrder();
      },
    });

    /**
     * 保存排序顺序
     */
    function saveOrder() {
      const postIds = [];
      
      $tbody.find('tr:not(.no-items)').each(function() {
        const $handle = $(this).find('.rena-sort-handle');
        const postId = $handle.data('post-id');
        if (postId) {
          postIds.push(postId);
        }
      });

      if (postIds.length === 0) {
        return;
      }

      // 显示保存中消息
      showMessage(renaSortable.strings.saving, 'saving');

      // 发送 AJAX 请求
      $.ajax({
        url: renaSortable.ajaxUrl,
        type: 'POST',
        data: {
          action: 'rena_save_post_order',
          nonce: renaSortable.nonce,
          post_type: renaSortable.postType,
          post_ids: postIds,
        },
        success: function(response) {
          if (response.success) {
            showMessage(renaSortable.strings.saved, 'success');
            
            // 更新每个行的 menu_order 数据属性
            $tbody.find('tr:not(.no-items)').each(function(index) {
              const $handle = $(this).find('.rena-sort-handle');
              $handle.attr('data-menu-order', index + 1);
            });
          } else {
            showMessage(renaSortable.strings.error, 'error');
          }
        },
        error: function() {
          showMessage(renaSortable.strings.error, 'error');
        },
      });
    }

    /**
     * 显示消息
     */
    function showMessage(message, type) {
      $message
        .removeClass('saving success error')
        .addClass('show ' + type)
        .html('<p>' + message + '</p>');

      // 3秒后自动隐藏成功消息
      if (type === 'success') {
        setTimeout(function() {
          $message.removeClass('show');
        }, 3000);
      }
    }

    // 添加视觉反馈：鼠标悬停时高亮行
    $tbody.on('mouseenter', 'tr', function() {
      if (!$(this).hasClass('no-items')) {
        $(this).css('background-color', '#f9f9f9');
      }
    }).on('mouseleave', 'tr', function() {
      $(this).css('background-color', '');
    });

    // 禁用原生的列标题排序链接（可选，如果需要的话）
    // $('.wp-list-table thead th.column-menu_order').find('a').click(function(e) {
    //   e.preventDefault();
    //   return false;
    // });
  });

})(jQuery);

