<html>
    
    
    <form action="<?= base_url() ?>client/upload_photo" method="post" enctype='multipart/form-data'>
        <input type="hidden" name="user_id" value="65"/>
        <input type="hidden" name="flag" value="N"/>
        <input type="hidden" name="img_url" value="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xtf1/v/t1.0-1/p320x320/12039649_132550387099614_8188787040890735912_n.jpg?oh=c3f24e2992d2db4bff5c84c0e73f3fef&oe=569DAD8C&__gda__=1453168121_77ed451c3d6063d7eb3e8d22e30b466d"/>
        <input type="file" name="file">
        <input type="submit" name="sub"/>
        
    </form>
    
    
</html>