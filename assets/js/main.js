/**
 * Interações do tema Root WP.
 */
(function () {
	'use strict';

	var root = document.documentElement;

	// Menu mobile e painel de busca.
	function bindToggle(buttonSelector, targetId, onOpen) {
		var button = document.querySelector(buttonSelector);
		var target = document.getElementById(targetId);
		if (!button || !target) return;

		button.addEventListener('click', function () {
			var open = target.classList.toggle('is-open');
			button.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (open && onOpen) onOpen(target);
		});
	}

	bindToggle('.menu-toggle', 'site-navigation');
	bindToggle('.search-toggle', 'search-panel', function (panel) {
		var field = panel.querySelector('input[type="search"]');
		if (field) field.focus();
	});

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') return;
		document.querySelectorAll('.is-open').forEach(function (el) {
			el.classList.remove('is-open');
		});
		document.querySelectorAll('[aria-expanded="true"]').forEach(function (el) {
			el.setAttribute('aria-expanded', 'false');
		});
	});

	// Modo escuro.
	var themeToggle = document.querySelector('.theme-toggle');
	if (themeToggle) {
		themeToggle.addEventListener('click', function () {
			var dark = root.getAttribute('data-theme') !== 'dark';
			if (dark) {
				root.setAttribute('data-theme', 'dark');
			} else {
				root.setAttribute('data-theme', 'light'); // explícito: plugins (ex.: CodePress) sabem que o site está claro
			}
			try {
				localStorage.setItem('as-theme', dark ? 'dark' : 'light');
			} catch (e) {}
		});
	}

	// Barra de progresso de leitura.
	var progress = document.querySelector('.reading-progress');
	var content = document.querySelector('.entry-content');
	if (progress && content) {
		var updateProgress = function () {
			var rect = content.getBoundingClientRect();
			var total = rect.height - window.innerHeight;
			var read = Math.min(Math.max(-rect.top, 0), Math.max(total, 1));
			progress.style.width = (total > 0 ? (read / total) * 100 : 100) + '%';
		};
		window.addEventListener('scroll', updateProgress, { passive: true });
		window.addEventListener('resize', updateProgress);
		updateProgress();
	}

	var reading = window.rootwpReading || {};

	// Botão "Copiar" nos blocos de código do artigo.
	if (reading.copy) {
		// Blocos do plugin CodePress têm botão próprio: ficam de fora.
		document.querySelectorAll('.entry-content pre:not(.codepress__pre)').forEach(function (pre) {
			// Envolve o <pre> para o botão ficar parado mesmo com rolagem horizontal do código.
			var wrap = document.createElement('div');
			wrap.className = 'code-block';
			pre.parentNode.insertBefore(wrap, pre);
			wrap.appendChild(pre);

			var button = document.createElement('button');
			button.type = 'button';
			button.className = 'code-copy';
			button.textContent = reading.copy;
			button.setAttribute('aria-label', reading.copyLabel);
			wrap.appendChild(button);

			button.addEventListener('click', function () {
				var text = pre.innerText.replace(/\n$/, '');
				var done = function () {
					button.textContent = reading.copied;
					button.classList.add('is-copied');
					setTimeout(function () {
						button.textContent = reading.copy;
						button.classList.remove('is-copied');
					}, 2000);
				};
				if (navigator.clipboard && window.isSecureContext) {
					navigator.clipboard.writeText(text).then(done);
				} else {
					// Reserva para sites sem HTTPS.
					var area = document.createElement('textarea');
					area.value = text;
					area.setAttribute('readonly', '');
					area.style.position = 'fixed';
					area.style.opacity = '0';
					document.body.appendChild(area);
					area.select();
					try { document.execCommand('copy'); done(); } catch (e) {}
					area.remove();
				}
			});
		});
	}

	// Tamanho do texto (A− / A / A+): de -1 a +3 passos de 10%, lembrado no navegador.
	var fontButtons = document.querySelectorAll('[data-font-step]');
	if (fontButtons.length) {
		var fontStep = 0;
		try { fontStep = parseInt(localStorage.getItem('rootwp-font-step'), 10) || 0; } catch (e) {}
		var applyFont = function () {
			root.style.setProperty('--reader-scale', 1 + fontStep * 0.1);
			fontButtons.forEach(function (btn) {
				var step = parseInt(btn.getAttribute('data-font-step'), 10);
				btn.disabled = (step === -1 && fontStep <= -1) || (step === 1 && fontStep >= 3);
				btn.setAttribute('aria-pressed', step === 0 && fontStep === 0 ? 'true' : 'false');
			});
			try { localStorage.setItem('rootwp-font-step', fontStep); } catch (e) {}
		};
		fontButtons.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var step = parseInt(btn.getAttribute('data-font-step'), 10);
				fontStep = step === 0 ? 0 : Math.min(3, Math.max(-1, fontStep + step));
				applyFont();
			});
		});
		applyFont();
	}

	// Tempo restante de leitura ("faltam 3 min"), no canto da tela.
	var remaining = document.querySelector('.reading-remaining');
	if (remaining && content) {
		var totalMinutes = parseInt(remaining.getAttribute('data-minutes'), 10) || 1;
		var hideTimer;
		var updateRemaining = function () {
			var rect = content.getBoundingClientRect();
			var total = rect.height - window.innerHeight;
			var ratio = total > 0 ? Math.min(Math.max(-rect.top / total, 0), 1) : 1;
			var inView = rect.top < window.innerHeight * 0.3 && rect.bottom > 0;

			if (!inView || totalMinutes < 2) {
				remaining.hidden = true;
				return;
			}
			clearTimeout(hideTimer);
			remaining.hidden = false;
			if (ratio >= 0.98) {
				remaining.textContent = reading.done;
				remaining.classList.add('is-done');
				hideTimer = setTimeout(function () { remaining.hidden = true; }, 2500);
			} else {
				var left = Math.ceil(totalMinutes * (1 - ratio));
				remaining.classList.remove('is-done');
				remaining.textContent = left <= 1 ? reading.lastMin : reading.remaining.replace('%d', left);
			}
		};
		window.addEventListener('scroll', updateRemaining, { passive: true });
		window.addEventListener('resize', updateRemaining);
		updateRemaining();
	}

	// Destaca no sumário a seção sendo lida.
	var tocLinks = document.querySelectorAll('.toc a');
	if (tocLinks.length && 'IntersectionObserver' in window) {
		var linkById = {};
		tocLinks.forEach(function (a) {
			linkById[decodeURIComponent(a.hash.slice(1))] = a;
		});

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				tocLinks.forEach(function (a) { a.classList.remove('is-active'); });
				var link = linkById[entry.target.id];
				if (link) link.classList.add('is-active');
			});
		}, { rootMargin: '-90px 0px -70% 0px' });

		Object.keys(linkById).forEach(function (id) {
			var heading = document.getElementById(id);
			if (heading) observer.observe(heading);
		});
	}

	// Vídeo do YouTube e Spotify: o player só é carregado quando o visitante clica na capa.
	document.addEventListener('click', function (e) {
		var facade = e.target.closest('[data-embed]');
		if (!facade) return;

		var iframe = document.createElement('iframe');
		iframe.src = facade.getAttribute('data-embed');
		iframe.title = facade.getAttribute('data-title') || '';
		iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; fullscreen; gyroscope; picture-in-picture; web-share';
		iframe.allowFullscreen = true;
		if (facade.getAttribute('data-height')) iframe.height = facade.getAttribute('data-height');

		facade.replaceWith(iframe);
		iframe.focus();
	});

	// Widget de vídeo do YouTube com controles próprios: play/pausa, volume e tela cheia.
	// Os controles do YouTube ficam ocultos (controls=0), o player é maior que a caixa
	// (o título e o logo ficam fora da área visível) e uma camada transparente impede
	// que o YouTube mostre sobreposições ao passar o mouse.
	var ytQueue = [];
	function withYouTubeApi(callback) {
		if (window.YT && window.YT.Player) { callback(); return; }
		ytQueue.push(callback);
		if (ytQueue.length > 1) return;
		var previous = window.onYouTubeIframeAPIReady;
		window.onYouTubeIframeAPIReady = function () {
			if (previous) previous();
			ytQueue.splice(0).forEach(function (fn) { fn(); });
		};
		var script = document.createElement('script');
		script.src = 'https://www.youtube.com/iframe_api';
		document.head.appendChild(script);
	}

	function videoStart(box) {
		return parseInt(box.getAttribute('data-start'), 10) || 0;
	}

	function syncVolume(box) {
		var player = box.ytPlayer;
		if (!player || !player.isMuted) return;
		var muted = player.isMuted() || player.getVolume() === 0;
		box.classList.toggle('is-muted', muted);
		box.querySelector('.as-video__range').value = muted ? 0 : player.getVolume();
	}

	function setupVideo(box, autoplay) {
		if (box.ytReady) {
			if (autoplay) box.ytPlayer.playVideo();
			return;
		}
		box.ytWantsPlay = box.ytWantsPlay || autoplay;
		if (box.ytLoading) return;
		box.ytLoading = true;
		box.classList.add('is-loading');

		withYouTubeApi(function () {
			box.ytPlayer = new YT.Player(box.querySelector('.as-video__player'), {
				host: 'https://www.youtube-nocookie.com',
				videoId: box.getAttribute('data-video'),
				playerVars: {
					autoplay: box.ytWantsPlay ? 1 : 0, controls: 0, disablekb: 1, fs: 0, rel: 0,
					iv_load_policy: 3, playsinline: 1, start: videoStart(box)
				},
				events: {
					onReady: function (e) {
						box.ytReady = true;
						box.classList.remove('is-loading');
						syncVolume(box);
						if (box.ytWantsPlay) e.target.playVideo();
					},
					onStateChange: function (e) {
						var S = YT.PlayerState;
						if (e.data === S.PLAYING || e.data === S.BUFFERING) {
							box.classList.add('is-started', 'is-playing');
							box.classList.remove('is-paused');
						} else if (e.data === S.PAUSED) {
							box.classList.remove('is-playing');
							box.classList.add('is-paused');
						} else if (e.data === S.ENDED) {
							// Fim do vídeo: volta para a capa, pronto para assistir de novo.
							box.classList.remove('is-started', 'is-playing', 'is-paused');
							e.target.seekTo(videoStart(box), false);
							e.target.pauseVideo();
						}
					}
				}
			});
		});
	}

	document.querySelectorAll('.as-video').forEach(function (box) {
		var fs = box.querySelector('.as-video__fs');
		var range = box.querySelector('.as-video__range');
		if (fs && !(document.fullscreenEnabled || document.webkitFullscreenEnabled)) fs.hidden = true;
		if (box.getAttribute('data-preload') === '1') setupVideo(box, false);

		box.addEventListener('click', function (e) {
			var player = box.ytReady ? box.ytPlayer : null;

			if (e.target.closest('.as-video__cover')) {
				setupVideo(box, true);
			} else if (e.target.closest('.as-video__toggle') || e.target.closest('.as-video__shield')) {
				if (!player) return;
				if (box.classList.contains('is-playing')) player.pauseVideo(); else player.playVideo();
			} else if (e.target.closest('.as-video__mute')) {
				if (!player) return;
				if (player.isMuted() || player.getVolume() === 0) {
					player.unMute();
					if (player.getVolume() === 0) player.setVolume(60);
				} else {
					player.mute();
				}
				setTimeout(function () { syncVolume(box); }, 50);
			} else if (e.target.closest('.as-video__fs')) {
				if (document.fullscreenElement || document.webkitFullscreenElement) {
					(document.exitFullscreen || document.webkitExitFullscreen).call(document);
				} else {
					(box.requestFullscreen || box.webkitRequestFullscreen).call(box);
				}
			}
		});

		if (range) {
			range.addEventListener('input', function () {
				var player = box.ytReady ? box.ytPlayer : null;
				var value = parseInt(range.value, 10);
				if (!player) return;
				player.setVolume(value);
				if (value === 0) player.mute(); else player.unMute();
				box.classList.toggle('is-muted', value === 0);
			});
		}
	});

	// Barra de aviso: ao fechar, lembra neste navegador (até o aviso mudar).
	var noticeBar = document.getElementById('notice-bar');
	if (noticeBar) {
		var closeNotice = noticeBar.querySelector('.notice-bar__close');
		if (closeNotice) {
			closeNotice.addEventListener('click', function () {
				try { localStorage.setItem('rootwp-notice', noticeBar.getAttribute('data-notice')); } catch (e) {}
				noticeBar.remove();
			});
		}
	}

	// Copiar link do artigo.
	document.querySelectorAll('.js-copy-link').forEach(function (button) {
		button.addEventListener('click', function () {
			var url = button.getAttribute('data-url');
			if (!navigator.clipboard) return;
			navigator.clipboard.writeText(url).then(function () {
				var label = button.getAttribute('aria-label');
				button.setAttribute('aria-label', 'Link copiado!');
				button.classList.add('is-copied');
				setTimeout(function () {
					button.setAttribute('aria-label', label);
					button.classList.remove('is-copied');
				}, 2000);
			});
		});
	});
})();
