<style>
#myBtn {
  display: block;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: #000;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
  margin-bottom:50px;
}
#myBtn:hover {
  background-color: #000;
}
</style>
<script>
function topFunction() {
	  document.body.scrollTop = 0;
	  document.documentElement.scrollTop = 0;
	}
</script>
<!--<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>-->

@if(!isset($no_padding))
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Powered by <a href="http://www.nltd.com/">Northern Lights Technology Development</a>
    </div>
    <strong>Copyright &copy; 2019
</footer>
@endif