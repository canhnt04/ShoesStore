<?php
require_once __DIR__ . "/../../../config/init.php";
?>

<div id="ajaxLoad">
    <div class="profile">
        <h2>Profile info</h2>

        <div class="profile-body">
            <!-- Sidebar -->
            <div class="profile-nav">
                <!-- Avatar + Tên -->
                <div class="profile-avatar">
                    <div class="item-img">
                        <img src="/ShoesStore/public/assets/images/avatar_default.jpg" alt="Avatar">
                    </div>
                    <div class="item-username">
                        <h4><?php echo htmlspecialchars($user['username']) ?? '' ?></h4>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <div class="profile-main">
                <!-- Info Section -->
                <form id="form-info" class="profile-content">
                    <div class="profile-content-item">
                        <span>Fullname</span>
                        <input type="text" name="fullname"
                            value="<?php echo htmlspecialchars($user['fullname']) ?? '' ?>"
                            data-old="<?php echo htmlspecialchars($user['fullname']) ?? '' ?>"
                            placeholder="Fullname">
                    </div>
                    <div class="profile-content-item">
                        <span>Phone number</span>
                        <input type="number" name="phone"
                            value="<?php echo htmlspecialchars($user['phone']) ?? '' ?>"
                            data-old="<?php echo htmlspecialchars($user['phone']) ?? '' ?>"
                            placeholder="Phone number">
                    </div>
                    <div class="profile-content-item">
                        <span>Address</span>
                        <input type="text" name="address"
                            value="<?php echo htmlspecialchars($user['address']) ?? '' ?>"
                            data-old="<?php echo htmlspecialchars($user['address']) ?? '' ?>"
                            placeholder="Address">
                    </div>
                    <button class="btnChange" type="submit">Update info</button>
                </form>
            </div>
        </div>
    </div>
</div>