require "test_helper"

class IdentiesControllerTest < ActionDispatch::IntegrationTest
  setup do
    @identy = identies(:one)
  end

  test "should get index" do
    get identies_url, as: :json
    assert_response :success
  end

  test "should create identy" do
    assert_difference("Identy.count") do
      post identies_url, params: { identy: { alamat: @identy.alamat, follower: @identy.follower, hobi: @identy.hobi, no_hp: @identy.no_hp, tgl_lahir: @identy.tgl_lahir } }, as: :json
    end

    assert_response :created
  end

  test "should show identy" do
    get identy_url(@identy), as: :json
    assert_response :success
  end

  test "should update identy" do
    patch identy_url(@identy), params: { identy: { alamat: @identy.alamat, follower: @identy.follower, hobi: @identy.hobi, no_hp: @identy.no_hp, tgl_lahir: @identy.tgl_lahir } }, as: :json
    assert_response :success
  end

  test "should destroy identy" do
    assert_difference("Identy.count", -1) do
      delete identy_url(@identy), as: :json
    end

    assert_response :no_content
  end
end
